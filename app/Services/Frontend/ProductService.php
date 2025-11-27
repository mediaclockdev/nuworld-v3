<?php

namespace App\Services\Frontend;

use App\Helpers\ApiResponse;
use App\Http\Resources\Api\Frontend\BestSellingProductResource;
use App\Http\Resources\Api\Frontend\ProductAttributeResource;
use App\Http\Resources\Api\Frontend\ProductResource;
use App\Http\Resources\Api\Frontend\SearchListProductResource;
use App\Models\{BestSeller, Pincode, Product, ProductAttribute, ProductAttributeValue, ProductCategory, ProductFilter, ProductVariant};
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;

class ProductService
{
  protected array $validSorts = [
    'relevance' => ['name', 'asc'],
    'most-recent' => ['created_at', 'desc'],
    'lowest-price' => ['sale_price', 'asc'],
    'highest-price' => ['sale_price', 'desc'],
  ];

  public function getProductVariantBySku(string $sku): ?ProductVariant
  {
    $productVariant = ProductVariant::where('sku', $sku)
      ->with([
        'category',
        'product',
        'variantAttributes.attributeValue',
        'images.gallery',
        'inventory'
      ])->first();

    return $productVariant;
  }

  public static function getProductVariants($productId = 0): Collection
  {
    return ProductVariant::where('product_id', $productId)
      ->with([
        'category',
        'product',
        'variantAttributes.attributeValue',
        'images.gallery',
        'inventory'
      ])->get();
  }

  public function getProductVariantByName(string $name): ProductVariant
  {
    $productVariant = ProductVariant::where('name', $name)
      ->with([
        'category',
        'product',
        'variantAttributes.attributeValue',
        'images.gallery'
      ])->first();

    return $productVariant;
  }

  public function getAttributeOptions(ProductVariant $currentVariant): array
  {
    if (!$currentVariant->relationLoaded('variantAttributes')) {
      $currentVariant->load('variantAttributes.attributeValue.attribute');
    }

    $productFilterAttributes = ProductFilter::where('product_id', $currentVariant->product_id)
      ->pluck('attribute_id')
      ->toArray();

    if (empty($productFilterAttributes)) {
      return ['attributes' => collect(), 'combinations' => collect()];
    }

    $allVariants = $this->getAllVariantsForProduct($currentVariant->product_id);

    $attributes = collect();
    foreach ($productFilterAttributes as $attributeId) {
      $attribute = ProductAttribute::find($attributeId);
      if (!$attribute) continue;

      $validValues = $allVariants->flatMap(function ($variant) use ($attributeId) {
        return $variant->variantAttributes->where('attribute_id', $attributeId)->pluck('attribute_value_id');
      })->unique()->values();

      $options = ProductAttributeValue::where('attribute_id', $attributeId)
        ->whereIn('id', $validValues)
        ->get()
        ->map(function ($value) use ($currentVariant, $allVariants, $attributeId) {
          $variant = $allVariants->first(function ($v) use ($attributeId, $value) {
            return $v->variantAttributes->contains(function ($attr) use ($attributeId, $value) {
              return $attr->attribute_id == $attributeId && $attr->attribute_value_id == $value->id;
            });
          });

          return $variant ? [
            'attribute_id' => $attributeId,
            'value' => $value->value,
            'attribute_value_id' => $value->id,
            'image_url' => $variant->images->first()->gallery->file_name ?? 'default-image.jpg',
            'sku' => $variant->sku,
          ] : null;
        })->filter()->values();

      if ($options->isNotEmpty()) {
        $attributes->push([
          'id' => $attributeId,
          'name' => $attribute->name,
          'options' => $options,
        ]);
      }
    }

    $combinations = $allVariants->map(function ($variant) use ($productFilterAttributes) {
      $combo = [];
      foreach ($productFilterAttributes as $attributeId) {
        $attr = $variant->variantAttributes->where('attribute_id', $attributeId)->first();
        if ($attr) {
          $combo[$attributeId] = $attr->attribute_value_id;
        }
      }
      return [
        'variant_id' => $variant->id,
        'sku' => $variant->sku,
        'attributes' => $combo,
      ];
    })->values();

    return [
      'attributes' => $attributes,
      'combinations' => $combinations,
    ];
  }

  public function getAllVariantsForProduct($productId)
  {
    return ProductVariant::with(['images.gallery', 'variantAttributes.attributeValue.attribute'])
      ->where('product_id', $productId)
      ->get();
  }

  public function searchVariants(
    ?Product $product,
    array $keywords,
    array $priceRange,
    array $params,
    int $perPage,
    int $page,
    string $url,
    array $queryParams,
    ?string $categorySlug = null
  ): LengthAwarePaginator {
    $variantQuery = $this->buildVariantQuery($product, $keywords, $priceRange, $params, $categorySlug);
    $groupedVariants = $product
      ? $variantQuery->get()
      : $variantQuery->get()->groupBy('product_id');

    return new LengthAwarePaginator(
      $groupedVariants->forPage($page, $perPage),
      $groupedVariants->count(),
      $perPage,
      $page,
      ['path' => $url, 'query' => $queryParams]
    );
  }

  //

  public function buildVariantQuery(?Product $product, array $keywords, array $priceRange, array $params)
  {
    $variantQuery = $product
      ? $product->variants()
      : ProductVariant::where('status', 1);

    $variantQuery = $this->applyKeywordRelevanceFilter($variantQuery, $keywords);
    $variantQuery->withCommonRelations(); // Scope added to ProductVariant model
    $variantQuery = $this->applyPriceFilter($variantQuery, $priceRange, $params);
    $variantQuery = $this->applyAttributeFilters($variantQuery, $params['attributes'] ?? []);

    return $this->applySorting($variantQuery, $this->getSortKey($params));
  }

  protected function applyKeywordRelevanceFilter($query, array $keywords)
  {
    if (empty($keywords)) return $query;

    $minMatch = count($keywords);
    $matchCases = array_map(
      fn($keyword) => "CASE WHEN name LIKE '%" . str_replace(['%', '_'], ['\%', '\_'], $keyword) . "%' THEN 1 ELSE 0 END",
      $keywords
    );
    $relevanceScore = implode(' + ', $matchCases);

    return $query
      ->selectRaw("*, ($relevanceScore) as relevance_score")
      ->where(fn($q) => array_map(fn($keyword) => $q->orWhere('name', 'like', "%$keyword%"), $keywords))
      ->havingRaw("relevance_score >= ?", [$minMatch])
      ->orderByDesc('relevance_score');
  }

  protected function applyPriceFilter($query, array $priceRange, array $params)
  {
    if (isset($params['min_price'], $params['max_price']) && $params['min_price'] !== '' && $params['max_price'] !== '') {
      return $query->whereBetween(DB::raw('COALESCE(sale_price, regular_price)'), [$params['min_price'], $params['max_price']]);
    }
    return $query;
  }

  protected function applyAttributeFilters($query, array $attributeFilters)
  {
    foreach ($attributeFilters as $attributeName => $values) {
      $values = is_array($values) ? array_filter($values) : array_filter(explode(',', $values));
      if (!empty($values)) {
        $query->whereHas(
          'variantAttributes',
          fn($q) => $q
            ->whereHas('attribute', fn($q2) => $q2->where('name', $attributeName))
            ->whereIn(
              'attribute_value_id',
              fn($q3) => $q3
                ->select('id')
                ->from('product_attribute_values')
                ->whereIn('value', $values)
            )
        );
      }
    }
    return $query;
  }

  protected function applySorting($query, string $sortKey)
  {
    $sort = $this->validSorts[$sortKey];
    if ($sort[0] === 'price') {
      return $query->orderByRaw("COALESCE(sale_price, regular_price) {$sort[1]}");
    }
    return $query->orderBy($sort[0], $sort[1]);
  }

  protected function getSortKey(array $params): string
  {
    return array_key_exists($params['sort'] ?? null, $this->validSorts) ? $params['sort'] : 'relevance';
  }

  public function getKeywords(array $params): array
  {
    $queryKeyword = $params['q'] ?? null;
    return $queryKeyword ? explode(' ', trim($queryKeyword)) : [];
  }

  public function getProductBySlug($productSlug)
  {
    $productName = Str::of($productSlug)->replace('-', ' ')->title();
    return Product::with(['category'])->where('name', $productName)->first();
  }

  public function getPriceRange($variantsOrProduct, array $params = []): array
  {
    if (!($variantsOrProduct instanceof Product || $variantsOrProduct instanceof Collection)) {
      throw new \InvalidArgumentException('Input must be a Product or Collection');
    }

    $prices = collect();

    if ($variantsOrProduct instanceof Product) {
      $prices = $variantsOrProduct->variants
        ->map(fn($v) => $this->getValidPrice($v->sale_price, $v->regular_price))
        ->filter();
    } elseif ($variantsOrProduct instanceof Collection) {
      $prices = $variantsOrProduct->flatMap(function ($variant) {
        return $variant->map(function ($value) {
          return $this->getValidPrice($value->sale_price, $value->regular_price);
        })->filter();
      })->filter();
    }

    $min = $prices->isEmpty() ? null : $prices->min();
    $max = $prices->isEmpty() ? null : $prices->max();

    return [
      'minPrice'       => $params['min_price'] ?? $min ?? 0,
      'maxPrice'       => $params['max_price'] ?? $max ?? 0,
      'actualMinPrice' => $min ?? 0,
      'actualMaxPrice' => $max ?? 0,
    ];
  }

  private function getValidPrice($salePrice, $regularPrice): ?float
  {
    $price = $salePrice ?? $regularPrice ?? null;
    return is_numeric($price) && $price > 0 ? (float) $price : null;
  }

  public function getFilterAttributes(?Product $product): Collection
  {
    if (!$product) {
      return collect();
    }

    return ProductAttribute::where('status', 1)
      ->with(['values' => function ($query) use ($product) {
        $query->whereIn('id', function ($subQuery) use ($product) {
          $subQuery->select('attribute_value_id')
            ->from('product_variant_attributes')
            ->whereIn('product_variant_id', $product->variants()->pluck('id'));
        })->orderBy('sequence');
      }])
      ->whereHas('values', function ($query) use ($product) {
        $query->whereIn('id', function ($subQuery) use ($product) {
          $subQuery->select('attribute_value_id')
            ->from('product_variant_attributes')
            ->whereIn('product_variant_id', $product->variants()->pluck('id'));
        });
      })
      ->orderBy('sequence')
      ->get();
  }

  public function getRelatedProducts(Product $product): Collection
  {
    return Product::where('category_id', $product->category_id)->get();
  }

  public function getBestSellingProducts()
  {
    $bestSellingProducts = BestSeller::with('product', 'variant.galleries')->get();
    return BestSellingProductResource::collection($bestSellingProducts);
  }

  public function getLatestProducts($limit, ?string $orderBy = null): Collection
  {
    $query = ProductVariant::with([
      'category',
      'images.gallery',
    ]);
    $query->when($orderBy, fn($q) => $q->orderBy('created_at', $orderBy === 'latest' ? 'desc' : 'asc'));
    return $limit !== null ? $query->take($limit)->get() : $query->get();
  }

  // public function getLatestProducts($limit = null): Collection
  // {
  //   // Step 1: Get latest variants ordered by created_at DESC
  //   // We fetch extra rows because many belong to same product, then unique by product_id
  //   $fetchCount = $limit ? $limit * 5 : 50; // safe buffer

  //   $variants = ProductVariant::with([
  //     'category',
  //     'product',
  //     'images.gallery',
  //   ])
  //     ->orderBy('created_at', 'desc')
  //     ->take($fetchCount)  // fetch extra to allow proper unique() behavior
  //     ->get();

  //   // Step 2: Reduce to one variant per product
  //   $uniqueLatest = $variants->unique('product_id')->values();

  //   // Step 3: Apply final limit
  //   return $limit ? $uniqueLatest->take($limit)->values() : $uniqueLatest;
  // }

  public function applyPincode(string $pincode = ''): array
  {
    $pincodeData = Pincode::where([
      ['code', $pincode],
      ['status', 1]
    ])->value('estimate_days');

    if (!$pincodeData)
      return [];

    return ['estimate_days' => $pincodeData];
  }


  public function productSearch()
  {
    $terms = array_filter(explode(' ', trim(request('q'))));

    $products = Product::with(['category', 'variants.images.gallery'])
      ->whereHas('variants', fn($q) => collect($terms)->each(
        fn($t) =>
        $q->where(
          fn($q2) =>
          $q2->where('name', 'like', "%$t%")->orWhere('sku', 'like', "%$t%")
        )
      ))->get();

    $results = $products->map(function ($product) use ($terms) {
      $matches = $product->variants->filter(function ($v) use ($terms) {
        $name = strtolower($v->name ?? '');
        $sku  = strtolower($v->sku ?? '');
        return collect($terms)->every(
          fn($t) =>
          str_contains($name, strtolower($t)) || str_contains($sku, strtolower($t))
        );
      });

      return $matches->isNotEmpty() ? [
        'product_id'   => Hashids::encode($product->id),
        'product_name' => $product->name,
        'category'     => $product->category->title ?? null,
        'match_count'  => $matches->count(),
        'variants'     => SearchListProductResource::collection($matches->take(2)),
      ] : null;
    })->filter()->take(10)->values();

    return $results;
  }

  public function filterProducts($request)
  {
    $params = $request->query();
    if (empty($params['q'] ?? null)) {
      return ApiResponse::error(__('validation.required', ['attribute' => 'Keyword']), 404);
    }

    $perPage = (int) $request->query('per_page', 12);
    $page = $request->get('page', 1);
    $keywords = $this->getKeywords($params);
    $priceRange = [
      'minPrice' => $params['min_price'] ?? null,
      'maxPrice' => $params['max_price'] ?? null,
      'actualMinPrice' => $params['min_price'] ?? null,
      'actualMaxPrice' => $params['max_price'] ?? null
    ];
    $variantQuery = $this->buildVariantQuery(null, $keywords, $priceRange, $params);
    $attributes = $this->getFilterAttributesFromVariants($variantQuery->get()->take($perPage));
    $variantsCollection = $variantQuery->get()->groupBy('product_id');
    $priceRange = $this->getPriceRange($variantsCollection, $params);

    // Create paginated variants
    $paginatedVariants = new LengthAwarePaginator(
      $variantsCollection->forPage($page, $perPage),
      $variantsCollection->count(),
      $perPage,
      $page,
      ['path' => $request->url(), 'query' => $request->query()]
    );

    // Flatten paginated variants for ProductResource
    $paginatedVariantItems = $paginatedVariants->getCollection()->flatMap(function ($variants) {
      return $variants; // Flatten the grouped variants
    });

    return [
      'keywords' => $keywords,
      'per_page' => $perPage,
      'page' => $page,
      'price_range' => $priceRange, // Removed redundant 'priceRange'
      'variants' => ProductResource::collection($paginatedVariantItems),
      'queryKeyword' => $params['q'] ?? null,
      'attributes' => ProductAttributeResource::collection($attributes),
      'selectedFilters' => $params['attributes'] ?? [],
    ];
  }

  protected function getFilterAttributesFromVariants($variants)
  {
    $attributeValueIds = $variants
      ->flatMap(function ($variant) {
        return $variant->variantAttributes->pluck('attribute_value_id');
      })
      ->unique()
      ->values();

    if ($attributeValueIds->isEmpty()) {
      return collect();
    }

    return ProductAttribute::where('status', 1)
      ->with(['values' => function ($query) use ($attributeValueIds) {
        $query->whereIn('id', $attributeValueIds)->orderBy('sequence');
      }])
      ->whereHas('values', function ($query) use ($attributeValueIds) {
        $query->whereIn('id', $attributeValueIds);
      })
      ->orderBy('sequence')
      ->get();
  }
}
