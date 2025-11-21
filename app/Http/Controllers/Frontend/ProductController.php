<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\{Product, ProductAttribute, ProductCategory, ProductVariant, SearchQuery};
use App\Services\Frontend\{ProductCardService, ProductService, BannerService};
use Illuminate\{Http\Request, Pagination\LengthAwarePaginator, Support\Facades\Auth, Support\Str};
use Illuminate\Support\Facades\Redirect;

class ProductController extends Controller
{
  protected $validSorts = [
    'relevance' => ['name', 'asc'],
    'most-recent' => ['created_at', 'desc'],
    'lowest-price' => ['price', 'asc'],
    'highest-price' => ['price', 'desc'],
  ];

  public function __construct(protected ProductService $productService, protected ProductCardService $productCardService, protected BannerService $bannerService) {}

  public function index() {}
  public function show($sku)
  {
    // Load the variant based on the SKU from the URL
    $productVariant = $this->productService->getProductVariantBySku($sku);

    // For Recently Viewed Products
    if (Auth::check()) {
      SearchQuery::log(
        query: $sku,
        userId: user()->id,
        ip: request()->ip()
      );
    } else {
      SearchQuery::log(
        query: $sku,
        userId: null,
        ip: request()->ip()
      );
      $lastLoggedSearchQueries = session('last_logged_search_query', []);

      if (!is_array($lastLoggedSearchQueries)) {
          $lastLoggedSearchQueries = [$lastLoggedSearchQueries];
      }

      $lastLoggedSearchQueries[] = trim(strtolower($sku));

      // remove duplicates & reindex
      $lastLoggedSearchQueries = array_values(array_unique($lastLoggedSearchQueries));

      session(['last_logged_search_query' => $lastLoggedSearchQueries]);
    }

    if (!$productVariant) {
      abort(404, 'Product variant not found.');
    }

    // Category data
    $category = $productVariant->category;
    $parentCategories = $category ? $category->allParents() : collect();
    $orderedImages = $productVariant->images->sortByDesc('is_default');

    // Get attributes and combinations for this variant
    $attributeOptionsData = $this->productService->getAttributeOptions($productVariant);
    $attributeOptions = $attributeOptionsData['attributes'];
    $combinations = $attributeOptionsData['combinations'];

    // Determine current selected attributes directly from the variant
    $currentAttributes = $productVariant->variantAttributes
      ->pluck('attribute_value_id', 'attribute_id');

    // Reviews logic
    $productReviews = collect();
    if (Auth::check()) {
      $user = Auth::user();
      $userId = $user->id;

      $hasCompletedOrder = $user->hasCompletedOrderForVariant($productVariant->id);

      $userReview = $productVariant->variantReviews()
        ->with('user')
        ->where('user_id', $userId)
        ->where('status', 1)
        ->first();

      if ($userReview) {
        $productReviews->push($userReview);
        $otherReviews = $productVariant->variantReviews()
          ->with('user')
          ->where('user_id', '!=', $userId)
          ->where('status', 1)
          ->orderByDesc('created_at')
          ->take(2)
          ->get();
        $productReviews = $productReviews->merge($otherReviews);
      } else {
        $productReviews = $productVariant->variantReviews()
          ->with('user')
          ->where('status', 1)
          ->orderByDesc('created_at')
          ->take(3)
          ->get();
      }
    } else {
      $productReviews = $productVariant->variantReviews()
        ->with('user')
        ->where('status', 1)
        ->orderByDesc('created_at')
        ->take(3)
        ->get();
    }

    $averageRating = $productReviews->avg('rating');
    $isInCart = isInCart($productVariant->id, false);
    $isInWishlist = isInCart($productVariant->id, true);
    $totalRatings = $productReviews->count();

    return view('frontend.pages.products-manage.show', [
      'title' => $productVariant->name ?? 'Product Details',
      'productVariant' => $productVariant,
      'category' => $category,
      'parentCategories' => $parentCategories,
      'attributeOptions' => $attributeOptions,
      'combinations' => $combinations,
      'orderedImages' => $orderedImages,
      'productReviews' => $productReviews,
      'averageRating' => $averageRating,
      'totalRatings' => $totalRatings,
      'isInCart' => $isInCart,
      'isInWishlist' => $isInWishlist,
      'hasCompletedOrder' => Auth::check() ? $hasCompletedOrder : null,
      'currentAttributes' => $currentAttributes,
    ]);
  }
  public function loadMoreReviews(Request $request)
  {
    $offset = $request->input('offset', 0);
    $sku = $request->input('sku');

    $productVariant = ProductVariant::where('sku', $sku)->first();

    $moreReviews = $productVariant->variantReviews()
      ->with('user')
      ->orderByDesc('created_at')
      ->skip($offset)
      ->take(3)
      ->get();

    $html = view('frontend.pages.user.includes.review-items', compact('moreReviews'))->render();

    return response()->json(['html' => $html, 'count' => $moreReviews->count()]);
  }

  public function search(Request $request, $productSlug = null)
  {
    $params = $request->query();
    if (is_null($productSlug) && empty($params['q'] ?? null)) {
      return redirect()->route('category.list');
    }
    $perPage = (int) $request->query('per_page', 12);
    $page = $request->get('page', 1);
    $keywords = $this->getKeywords($params);

    $product = $productSlug ? $this->getProductBySlug($productSlug) : null;

    if ($product) {
      $product->searchSlug = $productSlug;
    }

    $priceRange = $product
      ? $this->getPriceRange($product, $params)
      : [
        'minPrice' => $params['min_price'] ?? null,
        'maxPrice' => $params['max_price'] ?? null,
        'actualMinPrice' => $params['min_price'] ?? null,
        'actualMaxPrice' => $params['max_price'] ?? null
      ];

    $variantQuery = $this->productService->buildVariantQuery($product, $keywords, $priceRange, $params);

    $variantsCollection = $product
      ? $this->productCardService->groupVariants($variantQuery->get())
      : $variantQuery->get();

    if (!$product) {
      $priceRange = $this->getPriceRange($variantsCollection, $params);
    }

    $paginatedVariants = new LengthAwarePaginator(
      $variantsCollection->forPage($page, $perPage),
      $variantsCollection->count(),
      $perPage,
      $page,
      ['path' => $request->url(), 'query' => $request->query()]
    );

    $attributes = $product
      ? $this->getFilterAttributes($product)
      : $this->getFilterAttributesFromVariants($variantsCollection);

    $productCategories = collect();
    if (!$product) {
      $productIDs = $paginatedVariants->pluck('product_id');
      $productCategories = ProductCategory::whereHas('products', function ($query) use ($productIDs) {
        $query->whereIn('products.id', $productIDs);
      })->get();
    }

    //  $targetCategoryId = $category->parent->parent->id ?? 0;

    //     // Fetch all banners for 'shop_details_page_banner'
    //     $banners = $this->bannerService->getBanner('shop_details_page_banner', false);

    //     // Find banner that matches the target category ID in "single_option"
    //     $productListingBanner = collect($banners)->first(function ($banner) use ($targetCategoryId) {
    //         $settings = json_decode($banner['settings'], true);

    //         return isset($settings['single_option']) && $settings['single_option'] == $targetCategoryId;
    //     });

    if ($product) {
      $category = $product->category()->first();

        // Get the top-level (grandparent) category ID, if available
        $targetCategoryId = optional($category->parent->parent)->id
            ?? optional($category->parent)->id
            ?? optional($category)->id
            ?? 0;

        // Fetch all banners for 'shop_details_page_banner'
        $banners = $this->bannerService->getBanner('shop_details_page_banner', false);

        // Find banner that matches the target category ID in "single_option"
        $productListingBanner = collect($banners)->first(function ($banner) use ($targetCategoryId) {
            $settings = json_decode($banner['settings'], true);
            return isset($settings['single_option']) && $settings['single_option'] == $targetCategoryId;
        });

      return view('frontend.pages.products-manage.index', [
        'product' => $product,
        'variants' => $paginatedVariants,
        'queryKeyword' => $params['q'] ?? null,
        'relatedProducts' => $this->getRelatedProducts($product),
        'productListingBanner' => $productListingBanner,
        'priceRange' => $priceRange,
        'attributes' => $attributes,
        'selectedFilters' => $params['attributes'] ?? [],
      ]);
    }

    return view('frontend.pages.products-manage.search-products', [
      'variants' => $paginatedVariants,
      'queryKeyword' => $params['q'] ?? null,
      'productCategories' => $productCategories,
      'priceRange' => $priceRange,
      // 'priceRange' => $this->getPriceRange($variantsCollection, $params),
      'attributes' => $attributes,
      'selectedFilters' => $params['attributes'] ?? [],
      'productListingBanner' => $this->bannerService->getBanner('shop_page_banner', true),

    ]);
  }

  protected function getProductBySlug($productSlug)
  {
    $productName = Str::of($productSlug)->replace('-', ' ')->title();
    return Product::with(['category'])->where('name', $productName)->first();
  }

  protected function getKeywords(array $params)
  {
    $queryKeyword = $params['q'] ?? null;
    return $queryKeyword ? explode(' ', trim($queryKeyword)) : [];
  }

  protected function getPriceRange($variantsOrProduct, array $params): array
  {
    $prices = collect();

    if ($variantsOrProduct instanceof Product) {
      $prices = $variantsOrProduct->variants
        ->map(fn($v) => $v->sale_price > 0 ? $v->sale_price : ($v->regular_price > 0 ? $v->regular_price : null))
        ->filter();
    } elseif ($variantsOrProduct instanceof \Illuminate\Support\Collection) {
      $prices = $variantsOrProduct
        ->map(function ($v) {
          $price = $v->sale_price ?? $v->regular_price ?? null;
          return is_numeric($price) && $price > 0 ? $price : null;
        })
        ->filter();
    }

    $min = $prices->min() ?? 0;
    $max = $prices->max() ?? 0;

    return [
      'minPrice'       => $params['min_price'] ?? $min,
      'maxPrice'       => $params['max_price'] ?? $max,
      'actualMinPrice' => $params['min_price'] ?? $min,
      'actualMaxPrice' => $params['max_price'] ?? $max,
    ];
  }





  protected function getFilterAttributes(Product $product)
  {
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

  protected function getRelatedProducts(Product $product)
  {
    return Product::where('category_id', $product->category_id)->get();
  }

  // protected function getSortKey(Request $request)
  // {
  //   $requestedSort = $request->query('sort');
  //   return array_key_exists($requestedSort, $this->validSorts) ? $requestedSort : 'relevance';
  // }

  // protected function applySorting($query, string $sortKey)
  // {
  //   $sort = $this->validSorts[$sortKey];
  //   if ($sort[0] === 'price') {
  //     return $query->orderByRaw("COALESCE(sale_price, regular_price) {$sort[1]}");
  //   }
  //   return $query->orderBy($sort[0], $sort[1]);
  // }
}
