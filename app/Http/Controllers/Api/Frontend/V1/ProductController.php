<?php

namespace App\Http\Controllers\Api\Frontend\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Requests\Frontend\FilterRequest;
use App\Http\Resources\Api\Frontend\ColorOptionResource;
use App\Http\Resources\Api\Frontend\ProductDetailsResource;
use App\Http\Resources\Api\Frontend\ProductImageResource;
use App\Http\Resources\Api\Frontend\ProductResource;
use App\Http\Resources\Api\Frontend\ProductReviewResource;
use App\Models\Pincode;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\Frontend\ProductCardService;
use App\Services\Frontend\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Vinkla\Hashids\Facades\Hashids;

class ProductController extends Controller
{
  protected int $candidateLimit = 50; // how many candidates to fetch to score (autocomplete)
  protected int $autocompleteLimit = 8; // final suggestions to return
  public function __construct(protected ProductService $productService, protected ProductCardService $productCardService) {}

  public function bestSellingProducts()
  {
    $data = $this->productService->getBestSellingProducts();
    return ApiResponse::success($data, __('response.success.fetch', ['item' => 'Base Selling Products']));
  }

  public function getLatestProducts($limit = 16)
  {
    // Access this route with or without login
    ifApiTokenExists();
    $data = $this->productService->getLatestProducts($limit, 'latest');
    return ApiResponse::success(ProductResource::collection($data), __('response.success.fetch', ['item' => 'Latest Products']));
  }



  public function getProductVariants($productID)
  {
    ifApiTokenExists();

    $pid = Hashids::decode($productID)[0] ?? null;
    if (!$pid)
      return ApiResponse::error(__('response.not_found', ['item' => 'Product']), 404);

    $variants = $this->productService->getProductVariants($pid);

    if ($variants->isEmpty())
      return ApiResponse::error(__('response.not_found', ['item' => 'Product Variants']), 404);

    return ApiResponse::success(
      ProductResource::collection($variants),
      __('response.success.fetch', ['item' => 'Product Variants'])
    );
  }

  // public function getProductBySku(Request $request, $sku = null)
  // {
  //   ifApiTokenExists();
  //   $productVariant = $this->productService->getProductVariantBySku($sku);
  //   if (!$productVariant)
  //     return ApiResponse::error(__('response.not_found', ['item' => 'Product Variant']), 404);
  //   $orderedImages = $productVariant->images->sortByDesc('is_default');
  //   //$colorOptions = $this->productService->getAttributeOptions($productVariant);
  //   $colorOptions = [];
  //   //dd($colorOptions);
  //   // ------------------ Handle Reviews ------------------
  //   $productReviews = collect();

  //   if (auth()->check()) {
  //     $user = auth()->user();
  //     $userId = $user->id;

  //     $hasCompletedOrder = $user->hasCompletedOrderForVariant($productVariant->id); // optional to return

  //     $userReview = $productVariant->variantReviews()
  //       ->with('user')
  //       ->where('user_id', $userId)
  //       ->where('status', 1)
  //       ->first();

  //     if ($userReview) {
  //       $productReviews->push($userReview);

  //       $otherReviews = $productVariant->variantReviews()
  //         ->with('user')
  //         ->where('user_id', '!=', $userId)
  //         ->where('status', 1)
  //         ->orderByDesc('created_at')
  //         ->take(2)
  //         ->get();

  //       $productReviews = $productReviews->merge($otherReviews);
  //     } else {
  //       $productReviews = $productVariant->variantReviews()
  //         ->with('user')
  //         ->where('status', 1)
  //         ->orderByDesc('created_at')
  //         ->take(3)
  //         ->get();
  //     }
  //   } else {
  //     $productReviews = $productVariant->variantReviews()
  //       ->with('user')
  //       ->where('status', 1)
  //       ->orderByDesc('created_at')
  //       ->take(3)
  //       ->get();
  //   }
  //   $defaultPincode = config('defaults.default_pincode');

  //   $pincodeData = Pincode::where('status', 1)
  //     ->where('code', $defaultPincode)
  //     ->first(['estimate_days', 'code']);
  //   // $excludeProductId = $request->get('excludeProductId'); //excludeProductId
  //   // $checkout_products = $this->productCardService->getProductsWithVariants($excludeProductId);
  //   // ------------------ Checkout More Products ------------------

  //   // ------------------ Checkout More Products ------------------
  //   // Fetch products (not resources yet)
  //   $checkoutProducts = ProductVariant::where('status', 1)->where('product_id', '!=', $productVariant->product_id)->take(3)->get();

  //   // Apply excludeProductId filter
  //   // foreach ($checkoutProducts as $product) {
  //   //   if ($request->filled('excludeProductId')) {
  //   //     $excludeId = Hashids::decode($request->excludeProductId)[0] ?? null;
  //   //     $product->variants = $product->variants->where('product_id', '!=', $excludeId)->values();
  //   //   } else {
  //   //     $product->variants = $product->variants->values();
  //   //   }
  //   // }
  //   $data = [
  //     'product' => ProductDetailsResource::make($productVariant),
  //     'checkout_more_products' => ProductResource::collection($checkoutProducts),
  //     //'checkout_more_products' => ProductResource::collection($checkout_products),
  //     'color_options' => ColorOptionResource::collection($colorOptions),
  //     'images' => ProductImageResource::collection($orderedImages),
  //     'reviews' => ProductReviewResource::collection($productReviews),
  //     'pincodeData' => $pincodeData
  //   ];

  //   return ApiResponse::success($data, __('response.success.fetch', ['item' => 'Product']));
  // }

  public function getProductBySku(Request $request, $sku = null)
  {
    ifApiTokenExists();

    $productVariant = $this->productService->getProductVariantBySku($sku);
    if (!$productVariant) {
      return ApiResponse::error(__('response.not_found', ['item' => 'Product Variant']), 404);
    }

    // images
    // $orderedImages = $productVariant->images->sortByDesc('is_default');
    // pd($orderedImages);

    $images = $productVariant->images;

    $orderedImages = $images->count() === 1
      ? $images->sortByDesc('is_default')
      : $images->where('is_default', 0)->values();

    // --------- Attribute Options + Combinations ----------
    $attributeOptionsData = $this->productService->getAttributeOptions($productVariant);
    $attributeOptions = $attributeOptionsData['attributes'] ?? [];
    $combinations = $attributeOptionsData['combinations'] ?? [];

    // --------- CURRENT SELECTED ATTRIBUTES (Same as website) ----------
    $currentAttributes = $productVariant->variantAttributes
      ->pluck('attribute_value_id', 'attribute_id')
      ->mapWithKeys(fn($val, $key) => [(int)$key => (int)$val])
      ->toArray();

    // --------- FIND MATCHED SKU FUNCTION ----------
    $findMatchedSku = function ($combinations, $target) {
      foreach ($combinations as $combo) {
        $comboAttrs = [];
        foreach ($combo['attributes'] as $k => $v) {
          $comboAttrs[(int)$k] = (int)$v;
        }

        if ($comboAttrs === $target) {
          return $combo['sku'];
        }
      }
      return null;
    };

    // --------- MARK OPTIONS (is_current + matched_sku) ----------
    $attributeOptionsMarked = collect($attributeOptions)->map(function ($attribute) use ($currentAttributes, $combinations, $findMatchedSku) {

      $attributeId = (int)($attribute['id'] ?? $attribute['attribute_id']);
      $options = collect($attribute['options']);

      $updatedOptions = $options->map(function ($opt) use ($attributeId, $currentAttributes, $combinations, $findMatchedSku) {

        $valueId = (int)$opt['attribute_value_id'];

        // is_current
        $isCurrent = isset($currentAttributes[$attributeId]) &&
          $currentAttributes[$attributeId] === $valueId;

        // compute simulated new selection
        $simulated = $currentAttributes;
        $simulated[$attributeId] = $valueId;
        ksort($simulated);

        // matched SKU (same logic as website Blade)
        $matchedSku = $findMatchedSku($combinations, $simulated);

        return array_merge($opt, [
          'is_current' => $isCurrent,
          'matched_sku' => $matchedSku,
        ]);
      })->values();

      return array_merge($attribute, [
        'options' => $updatedOptions
      ]);
    })->values();


    // ------------------ REVIEWS ------------------
    $productReviews = collect();

    if (auth()->check()) {
      $user = auth()->user();
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

    $defaultPincode = config('defaults.default_pincode');

    $pincodeData = Pincode::where('status', 1)
      ->where('code', $defaultPincode)
      ->first(['estimate_days', 'code']);

    // Checkout more products
    $checkoutProducts = ProductVariant::where('status', 1)
      ->where('product_id', '!=', $productVariant->product_id)
      ->take(3)
      ->get();

    // ------------------ FINAL RESPONSE DATA ------------------
    $data = [
      'product' => ProductDetailsResource::make($productVariant),

      'attribute_options' => $attributeOptionsMarked,
      'current_attributes' => $currentAttributes,
      'combinations' => $combinations,

      'checkout_more_products' => ProductResource::collection($checkoutProducts),
      'images' => ProductImageResource::collection($orderedImages),
      'reviews' => ProductReviewResource::collection($productReviews),
      'pincodeData' => $pincodeData,
    ];

    return ApiResponse::success($data, __('response.success.fetch', ['item' => 'Product']));
  }

  // public function searchProduct()
  // {
  //   if (!request()->has('q'))
  //     return ApiResponse::error(__('validation.required', ['attribute' => 'Keyword']), 404);
  //   ifApiTokenExists();
  //   $products = $this->productService->productSearch();
  //   return ApiResponse::success($products, __('response.success.fetch', ['item' => 'Product']));
  // }

  public function applyPincode(Request $request)
  {
    $data = $this->productService->applyPincode($request->pincode);
    if (empty($data))
      return ApiResponse::error(__('response.error.not_serviceable', ['item' => 'Pincode']));

    return ApiResponse::success($data, __('response.success.serviceable', ['item' => 'Pincode']));
  }

  public function filterProducts(FilterRequest $request)
  {
    ifApiTokenExists();
    $data = $this->productService->filterProducts($request);
    return ApiResponse::success($data, __('response.success.fetch', ['item' => 'Products']));
  }


  public function autocomplete(Request $request)
  {
    //dd('here');
    ifApiTokenExists();
    $q = trim((string) $request->query('q', ''));

    if ($q === '') {
      return response()->json(['success' => true, 'data' => []]);
    }

    $searchQuery = strtolower(preg_replace('/\s+/', ' ', $q));
    $searchTerms = array_filter(explode(' ', $searchQuery));

    // 1) exact-matching step (matches all terms) — try to return fast if available
    $exactVariants = $this->fetchVariantsMatchingAllTerms($searchTerms);
    if ($exactVariants->isNotEmpty()) {
      $results = $exactVariants->take($this->autocompleteLimit);
      return response()->json(['success' => true, 'data' => ProductResource::collection($results)->resolve()]);
    }

    // 2) fuzzy step: fetch candidate set then score
    $candidates = $this->fetchCandidatesAnyTerm($searchTerms);

    if ($candidates->isEmpty()) {
      return response()->json(['success' => true, 'data' => []]);
    }

    // Score candidates: similar_text on name & sku, boost prefix and exact matches
    $scored = $candidates->map(function ($v) use ($searchQuery) {
      $name = strtolower((string)($v->name ?? ''));
      $sku = strtolower((string)($v->sku ?? ''));

      similar_text($searchQuery, $name, $percentName);
      similar_text($searchQuery, $sku, $percentSku);

      $boost = 0;
      if ($sku === $searchQuery) $boost += 40;
      if ($name === $searchQuery) $boost += 30;
      if (Str::startsWith($name, $searchQuery) || Str::startsWith($sku, $searchQuery)) $boost += 10;

      $v->match_score = max($percentName, $percentSku) + $boost;

      return $v;
    });

    $results = $scored->filter(fn($v) => ($v->match_score ?? 0) > 8)
      ->sortByDesc('match_score')
      ->values()
      ->take($this->autocompleteLimit);

    return response()->json(['success' => true, 'data' => ProductResource::collection($results)->resolve()]);
  }

  /**
   * Full search (paginated) for mobile search results page
   * GET /api/v1/products/search?q=...&page=1&per_page=12&min_price=&max_price=&sort=
   */
  public function search(Request $request)
  {
    ifApiTokenExists();
    $q = trim((string)$request->query('q', ''));
    $page = max(1, (int)$request->query('page', 1));
    $perPage = min(100, max(8, (int)$request->query('per_page', 12)));

    $query = ProductVariant::query()
      ->with(['product:id,name,category_id', 'product.category:id,title', 'galleries', 'inventory', 'variantReviews'])
      ->where('status', 1);

    if ($q !== '') {
      $searchQuery = strtolower(preg_replace('/\s+/', ' ', $q));
      $terms = array_filter(explode(' ', $searchQuery));
      $query->where(function ($qq) use ($terms) {
        foreach ($terms as $t) {
          $term = '%' . $t . '%';
          $qq->orWhere('sku', 'like', $term)
            ->orWhere('name', 'like', $term)
            ->orWhereHas('product', fn($qp) => $qp->where('name', 'like', $term));
        }
      });
    }

    // price filter
    if ($request->filled('min_price') || $request->filled('max_price')) {
      $min = $request->query('min_price', null);
      $max = $request->query('max_price', null);
      if ($min !== null && $max !== null) {
        $query->whereRaw('COALESCE(sale_price, regular_price) BETWEEN ? AND ?', [$min, $max]);
      } elseif ($min !== null) {
        $query->whereRaw('COALESCE(sale_price, regular_price) >= ?', [$min]);
      } elseif ($max !== null) {
        $query->whereRaw('COALESCE(sale_price, regular_price) <= ?', [$max]);
      }
    }

    // sorting shorthand
    $sort = $request->query('sort', 'most-recent');
    if ($sort === 'lowest-price') {
      $query->orderByRaw('COALESCE(sale_price, regular_price) asc');
    } elseif ($sort === 'highest-price') {
      $query->orderByRaw('COALESCE(sale_price, regular_price) desc');
    } else {
      $query->orderByDesc('created_at');
    }

    $paginator = $query->paginate($perPage, ['*'], 'page', $page);

    $data = ProductResource::collection(collect($paginator->items()))->resolve();

    return response()->json([
      'success' => true,
      'payload' => [
        'data' => $data,
        'meta' => [
          'total' => $paginator->total(),
          'per_page' => $paginator->perPage(),
          'current_page' => $paginator->currentPage(),
          'last_page' => $paginator->lastPage(),
        ]
      ]
    ]);
  }

  protected function fetchVariantsMatchingAllTerms(array $terms)
  {
    if (empty($terms)) return collect();

    // Query products that have variants matching all terms, then flatten variants like your Livewire
    $products = Product::with(['category', 'variants.images.gallery'])
      ->whereHas('variants', function ($q) use ($terms) {
        foreach ($terms as $term) {
          $q->where(function ($sub) use ($term) {
            $sub->where('name', 'like', '%' . $term . '%')
              ->orWhere('sku', 'like', '%' . $term . '%');
          });
        }
      })
      ->get();

    $matched = $products->flatMap(function ($product) use ($terms) {
      return $product->variants->filter(function ($variant) use ($terms) {
        $name = strtolower($variant->name ?? '');
        $sku = strtolower($variant->sku ?? '');
        foreach ($terms as $term) {
          $t = strtolower($term);
          if (!str_contains($name, $t) && !str_contains($sku, $t)) return false;
        }
        return true;
      })->map(function ($variant) use ($product) {
        $variant->product_name = $product->name;
        $variant->category_name = $product->category->title ?? 'No Category';
        $variant->match_score = 100;
        return $variant;
      });
    });

    return $matched;
  }

  // Fetch candidate variants that match ANY term (limit)
  protected function fetchCandidatesAnyTerm(array $terms)
  {
    if (empty($terms)) return collect();

    $query = ProductVariant::with(['product', 'product.category', 'galleries', 'inventory', 'variantReviews'])
      ->where('status', 1)
      ->where(function ($q) use ($terms) {
        foreach ($terms as $t) {
          $term = '%' . $t . '%';
          $q->orWhere('sku', 'like', $term)
            ->orWhere('name', 'like', $term);
        }
      })
      ->orderByDesc('created_at')
      ->limit($this->candidateLimit);

    return $query->get();
  }
}
