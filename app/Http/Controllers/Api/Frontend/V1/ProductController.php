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
use App\Models\ProductAttribute;
use App\Models\ProductCategory;
use App\Models\ProductVariant;
use App\Services\Frontend\ProductCardService;
use App\Services\Frontend\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
  protected int $candidateLimit = 50; // how many candidates to fetch to score (autocomplete)
  protected int $autocompleteLimit = 8; // final suggestions to return
  protected int $facetSampleLimit = 500;   // sample size for facets
  protected int $facetCacheTtl = 30;       // seconds

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

  // public function tryOn(Request $request)
  // {
  //   ifApiTokenExists(); // keep your auth if needed

  //   $request->validate([
  //     'avatar'  => 'required|image',
  //     'product' => 'required|image',
  //   ]);

  //   // 1. Save uploads to public disk
  //   $avatarPath  = $request->file('avatar')->store('avatars', 'public');
  //   $productPath = $request->file('product')->store('products', 'public');

  //   // 2. Build full paths (Windows safe)
  //   $avatarFullPath  = storage_path('app/public/' . $avatarPath);
  //   $productFullPath = storage_path('app/public/' . $productPath);

  //   if (!file_exists($avatarFullPath)) {
  //     return response()->json(['success' => false, 'error' => 'Avatar file missing'], 500);
  //   }

  //   if (!file_exists($productFullPath)) {
  //     return response()->json(['success' => false, 'error' => 'Product file missing'], 500);
  //   }

  //   // 3. API4AI DEMO endpoint (NO API KEY REQUIRED)
  //   $url = 'https://demo.api4ai.cloud/virtual-try-on/v1/results';
  //   //  $url = 'https://api4.ai/v1/virtual-try-on/results';

  //   // 4. Call API
  //   try {
  //     $response = Http::withoutVerifying()
  //       ->timeout(300)
  //       ->attach('image', fopen($avatarFullPath, 'r'), 'person.jpg')
  //       ->attach('image-apparel', fopen($productFullPath, 'r'), 'cloth.jpg')
  //       ->post($url);
  //   } catch (\Exception $e) {
  //     return response()->json([
  //       'success' => false,
  //       'error' => 'API connection failed: ' . $e->getMessage()
  //     ], 500);
  //   }

  //   if (!$response->successful()) {
  //     return response()->json([
  //       'success' => false,
  //       'error' => 'Try-On API error',
  //       'details' => $response->body()
  //     ], 500);
  //   }

  //   // 5. Parse API response
  //   $json = $response->json();

  //   // API4AI response format
  //   $base64Img = $json['results'][0]['entities'][0]['image'] ?? null;

  //   if (!$base64Img) {
  //     return response()->json([
  //       'success' => false,
  //       'error' => 'No image returned from API',
  //       'api_response' => $json
  //     ], 500);
  //   }

  //   // 6. Save generated image
  //   $imageData = base64_decode($base64Img);
  //   $resultPath = 'tryon/' . uniqid() . '.png';
  //   Storage::disk('public')->put($resultPath, $imageData);

  //   // 7. Return result URL
  //   return response()->json([
  //     'success'    => true,
  //     'result_url' => asset('public/storage/' . $resultPath),
  //   ]);
  // }

  public function tryOn(Request $request)
  {
    ifApiTokenExists(); // your auth

    $request->validate([
      'avatar_url'  => 'required|url',
      'product_url' => 'required|url',
    ]);

    // 1. Download images from URLs
    try {
      $avatarContent  = file_get_contents($request->avatar_url);
      $productContent = file_get_contents($request->product_url);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'error' => 'Failed to download images from URLs'
      ], 400);
    }

    // 2. Save to temp storage
    $avatarPath  = 'avatars/' . uniqid() . '.jpg';
    $productPath = 'products/' . uniqid() . '.jpg';

    Storage::disk('public')->put($avatarPath, $avatarContent);
    Storage::disk('public')->put($productPath, $productContent);

    $avatarFullPath  = storage_path('app/public/' . $avatarPath);
    $productFullPath = storage_path('app/public/' . $productPath);

    if (!file_exists($avatarFullPath) || !file_exists($productFullPath)) {
      return response()->json([
        'success' => false,
        'error' => 'Failed to save downloaded images'
      ], 500);
    }

    // 3. API4AI DEMO endpoint
    $url = 'https://demo.api4ai.cloud/virtual-try-on/v1/results';

    // 4. Call Try-On API
    try {
      $response = Http::withoutVerifying()
        ->timeout(300)
        ->attach('image', fopen($avatarFullPath, 'r'), 'person.jpg')
        ->attach('image-apparel', fopen($productFullPath, 'r'), 'cloth.jpg')
        ->post($url);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'error' => 'Try-On API connection failed: ' . $e->getMessage()
      ], 500);
    }

    if (!$response->successful()) {
      return response()->json([
        'success' => false,
        'error'   => 'Try-On API error',
        'details' => $response->body()
      ], 500);
    }

    // 5. Parse API response
    $json = $response->json();

    $base64Img = $json['results'][0]['entities'][0]['image'] ?? null;

    if (!$base64Img) {
      return response()->json([
        'success' => false,
        'error' => 'No image returned from Try-On API',
        'api_response' => $json
      ], 500);
    }

    // 6. Save generated image
    $imageData = base64_decode($base64Img);
    $resultPath = 'tryon/' . uniqid() . '.png';
    Storage::disk('public')->put($resultPath, $imageData);

    // 7. Return final URL
    return response()->json([
      'success'    => true,
      'result_url' => asset('public/storage/' . $resultPath),
    ]);
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
      ->whereHas('product', function ($query) use ($productVariant) {
        $query->where('category_id', $productVariant->product->category_id);
      })
      ->inRandomOrder()
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

  public function attributeOptions(Request $request)
  {
    ifApiTokenExists();
    $mode = $request->query('mode', 'contextual'); // 'full' or 'contextual'
    if ($mode === 'full') {
      $attrs = ProductAttribute::where('status', 1)->with(['values' => function ($q) {
        $q->orderBy('sequence');
      }])->orderBy('sequence')->get();
      return response()->json(['success' => true, 'mode' => 'full', 'payload' => ['attributes' => $attrs]]);
    }

    // contextual: build a similar variant query to web (lightweight)
    $q = trim((string)$request->query('q', ''));
    $categorySlug = $request->query('category_slug', null);
    $limitScan = (int) $request->query('limit_variants_scan', 500);

    $variantQuery = ProductVariant::query()->where('status', 1);

    if ($q !== '') {
      $terms = array_filter(explode(' ', strtolower(preg_replace('/\s+/', ' ', $q))));
      foreach ($terms as $term) {
        $wild = "%$term%";
        $variantQuery->where(function ($qq) use ($wild) {
          $qq->where('name', 'like', $wild)->orWhere('sku', 'like', $wild)->orWhereHas('product', fn($p) => $p->where('name', 'like', $wild));
        });
      }
    }
    if ($categorySlug) {
      $cat = ProductCategory::where('slug', $categorySlug)->first();
      if ($cat) {
        $variantQuery->whereHas('product', fn($p) => $p->where('category_id', $cat->id));
      }
    }

    $variantIds = $variantQuery->limit($limitScan)->pluck('id')->toArray();
    if (empty($variantIds)) {
      return response()->json(['success' => true, 'mode' => 'contextual', 'payload' => ['attributes' => []]]);
    }

    $attributeValueIds = DB::table('product_variant_attributes')->whereIn('product_variant_id', $variantIds)
      ->pluck('attribute_value_id')->unique()->values()->toArray();

    if (empty($attributeValueIds)) {
      return response()->json(['success' => true, 'mode' => 'contextual', 'payload' => ['attributes' => []]]);
    }

    $attributes = ProductAttribute::where('status', 1)
      ->with(['values' => function ($q) use ($attributeValueIds) {
        $q->whereIn('id', $attributeValueIds)->orderBy('sequence');
      }])
      ->whereHas('values', fn($q) => $q->whereIn('id', $attributeValueIds))
      ->orderBy('sequence')
      ->get();

    return response()->json(['success' => true, 'mode' => 'contextual', 'payload' => ['attributes' => $attributes]]);
  }

  // public function search(Request $request)
  // {
  //   ifApiTokenExists();
  //   $q = trim((string)$request->query('q', ''));
  //   $page = max(1, (int)$request->query('page', 1));
  //   $perPage = min(48, max(8, (int)$request->query('per_page', 12)));
  //   $minPrice = $request->query('min_price', null);
  //   $maxPrice = $request->query('max_price', null);
  //   $sort = $request->query('sort', 'most-recent');
  //   $attributesInput = $request->query('attributes', []); // supports name-based or id-based
  //   $categorySlug = $request->query('category_slug', null);

  //   // Build the variant query — try to reuse your web productService if available
  //   if (property_exists($this, 'productService') && method_exists($this->productService, 'buildVariantQuery')) {
  //     $keywords = $q ? explode(' ', $q) : [];
  //     $priceRange = [
  //       'minPrice' => $minPrice,
  //       'maxPrice' => $maxPrice,
  //       'actualMinPrice' => $minPrice,
  //       'actualMaxPrice' => $maxPrice
  //     ];
  //     $variantQuery = $this->productService->buildVariantQuery(null, $keywords, $priceRange, $request->query());
  //   } else {
  //     // Fallback simple query (mirrors earlier examples)
  //     $variantQuery = ProductVariant::query()->with(['product', 'product.category', 'galleries', 'inventory', 'variantReviews'])
  //       ->where('status', 1);

  //     if ($q !== '') {
  //       $terms = array_filter(explode(' ', strtolower(preg_replace('/\s+/', ' ', $q))));
  //       foreach ($terms as $term) {
  //         $wild = "%{$term}%";
  //         $variantQuery->where(function ($qq) use ($wild) {
  //           $qq->where('name', 'like', $wild)
  //             ->orWhere('sku', 'like', $wild)
  //             ->orWhereHas('product', fn($p) => $p->where('name', 'like', $wild));
  //         });
  //       }
  //     }

  //     if ($categorySlug) {
  //       $cat = ProductCategory::where('slug', $categorySlug)->first();
  //       if ($cat) {
  //         $variantQuery->whereHas('product', fn($p) => $p->where('category_id', $cat->id));
  //       }
  //     }
  //   }

  //   // Apply attribute filters (supports both name-based and id-based)
  //   $selectedAttributeValueIds = $this->applyAttributeFiltersToQuery($variantQuery, $attributesInput);

  //   // Apply price filters
  //   if ($minPrice !== null || $maxPrice !== null) {
  //     if ($minPrice !== null && $maxPrice !== null) {
  //       $variantQuery->whereRaw('COALESCE(sale_price, regular_price) BETWEEN ? AND ?', [(float)$minPrice, (float)$maxPrice]);
  //     } elseif ($minPrice !== null) {
  //       $variantQuery->whereRaw('COALESCE(sale_price, regular_price) >= ?', [(float)$minPrice]);
  //     } elseif ($maxPrice !== null) {
  //       $variantQuery->whereRaw('COALESCE(sale_price, regular_price) <= ?', [(float)$maxPrice]);
  //     }
  //   }

  //   // Sorting
  //   if ($sort === 'lowest-price') {
  //     $variantQuery->orderByRaw('COALESCE(sale_price, regular_price) asc');
  //   } elseif ($sort === 'highest-price') {
  //     $variantQuery->orderByRaw('COALESCE(sale_price, regular_price) desc');
  //   } elseif ($sort === 'top-rated') {
  //     // ensure avg rating column is available, then order by it (NULL -> 0)
  //     $variantQuery->withAvg('variantReviews', 'rating')
  //       ->orderByRaw('COALESCE(variant_reviews_avg_rating, 0) desc');
  //   } else {
  //     // most-recent (default)
  //     $variantQuery->orderByDesc('created_at');
  //   }

  //   // Paginate (DB pagination)
  //   $paginator = $variantQuery->paginate($perPage, ['*'], 'page', $page);
  //   $items = collect($paginator->items());
  //   $products = ProductResource::collection($items)->resolve();

  //   // Price range for UI (exact min/max across matching variants)
  //   $priceRange = $this->computePriceRangeForQuery(clone $variantQuery);

  //   // Facets (attributes + counts) — use contextual sample
  //   $facets = $this->computeFacetsForQuery($variantQuery, $attributesInput, $this->facetSampleLimit, $this->facetCacheTtl);

  //   return response()->json([
  //     'success' => true,
  //     'payload' => [
  //       'data' => $products,
  //       'meta' => [
  //         'total' => $paginator->total(),
  //         'per_page' => $paginator->perPage(),
  //         'current_page' => $paginator->currentPage(),
  //         'last_page' => $paginator->lastPage(),
  //       ],
  //       'price_range' => $priceRange,
  //       'facets' => $facets,
  //       'selected_filters' => $attributesInput
  //     ]
  //   ]);
  // }

  public function search(Request $request)
  {
    ifApiTokenExists();
    $q = trim((string)$request->query('q', ''));
    $page = max(1, (int)$request->query('page', 1));
    $perPage = min(48, max(8, (int)$request->query('per_page', 12)));
    $minPrice = $request->query('min_price', null);
    $maxPrice = $request->query('max_price', null);
    $sort = $request->query('sort', 'most-recent');
    $attributesInput = $request->query('attributes', []); // supports name-based or id-based
    $categorySlug = $request->query('category_slug', null);

    // Build the variant query — reuse your productService if available
    if (property_exists($this, 'productService') && method_exists($this->productService, 'buildVariantQuery')) {
      $keywords = $q ? explode(' ', $q) : [];
      $priceRange = [
        'minPrice' => $minPrice,
        'maxPrice' => $maxPrice,
        'actualMinPrice' => $minPrice,
        'actualMaxPrice' => $maxPrice
      ];
      $variantQuery = $this->productService->buildVariantQuery(null, $keywords, $priceRange, $request->query());
    } else {
      // Fallback simple query (mirrors earlier examples)
      $variantQuery = ProductVariant::query()->with(['product', 'product.category', 'galleries', 'inventory', 'variantReviews'])
        ->where('status', 1);

      if ($q !== '') {
        $terms = array_filter(explode(' ', strtolower(preg_replace('/\s+/', ' ', $q))));
        foreach ($terms as $term) {
          $wild = "%{$term}%";
          $variantQuery->where(function ($qq) use ($wild) {
            $qq->where('name', 'like', $wild)
              ->orWhere('sku', 'like', $wild)
              ->orWhereHas('product', fn($p) => $p->where('name', 'like', $wild));
          });
        }
      }

      if ($categorySlug) {
        $cat = ProductCategory::where('slug', $categorySlug)->first();
        if ($cat) {
          $variantQuery->whereHas('product', fn($p) => $p->where('category_id', $cat->id));
        }
      }
    }

    // Apply attribute filters (supports both name-based and id-based)
    $selectedAttributeValueIds = $this->applyAttributeFiltersToQuery($variantQuery, $attributesInput);

    // Apply price filters
    if ($minPrice !== null || $maxPrice !== null) {
      if ($minPrice !== null && $maxPrice !== null) {
        $variantQuery->whereRaw('COALESCE(sale_price, regular_price) BETWEEN ? AND ?', [(float)$minPrice, (float)$maxPrice]);
      } elseif ($minPrice !== null) {
        $variantQuery->whereRaw('COALESCE(sale_price, regular_price) >= ?', [(float)$minPrice]);
      } elseif ($maxPrice !== null) {
        $variantQuery->whereRaw('COALESCE(sale_price, regular_price) <= ?', [(float)$maxPrice]);
      }
    }

    // ---------- Decide non-top-rated ordering early (we will handle top-rated specially) ----------
    if ($sort === 'lowest-price') {
      // We'll still use the two-step ordering later; keeping this here to influence pluck order if any
      $variantQuery->orderByRaw('COALESCE(sale_price, regular_price) asc');
    } elseif ($sort === 'highest-price') {
      $variantQuery->orderByRaw('COALESCE(sale_price, regular_price) desc');
    } else {
      // do not set final ordering for top-rated here; top-rated handled in second step
      $variantQuery->orderByDesc('created_at');
    }

    // ---------------- TWO-STEP: get matching variant IDs (respect all filters) ----------------
    // cap to avoid huge memory usage; increase if your app needs it
    $maxIdsCap = 5000;
    $matchingIds = (clone $variantQuery)->limit($maxIdsCap)->pluck('id')->toArray();

    // If no matches, return empty paginated response
    if (empty($matchingIds)) {
      $emptyPaginator = new LengthAwarePaginator(
        collect([]),
        0,
        $perPage,
        $page,
        ['path' => $request->url(), 'query' => $request->query()]
      );

      return response()->json([
        'success' => true,
        'payload' => [
          'data' => [],
          'meta' => [
            'total' => 0,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => 0,
          ],
          'price_range' => $this->computePriceRangeForQuery(clone $variantQuery),
          'facets' => [],
          'selected_filters' => $attributesInput
        ]
      ]);
    }

    // ---------------- Build aggregated ratings subquery for these IDs ----------------
    $ratingsSub = DB::table('product_reviews')
      ->select('variant_id', DB::raw('AVG(rating) as avg_rating'), DB::raw('COUNT(*) as review_count'))
      ->whereIn('variant_id', $matchingIds)
      ->groupBy('variant_id');

    // ---------------- Build ordered query joining the ratings subquery ----------------
    $orderedQuery = ProductVariant::query()
      ->with(['product', 'product.category', 'galleries', 'inventory', 'variantReviews'])
      ->whereIn('product_variants.id', $matchingIds)
      ->leftJoinSub($ratingsSub, 'rv', 'rv.variant_id', 'product_variants.id')
      ->select('product_variants.*', DB::raw('COALESCE(rv.avg_rating, 0) as avg_rating'), DB::raw('COALESCE(rv.review_count, 0) as review_count'));

    // Apply final ordering depending on requested sort
    if ($sort === 'top-rated') {
      $orderedQuery->orderByRaw('avg_rating DESC');
      $orderedQuery->orderByRaw('review_count DESC');
      $orderedQuery->orderByDesc('product_variants.created_at');
    } elseif ($sort === 'lowest-price') {
      $orderedQuery->orderByRaw('COALESCE(sale_price, regular_price) asc');
    } elseif ($sort === 'highest-price') {
      $orderedQuery->orderByRaw('COALESCE(sale_price, regular_price) desc');
    } else {
      $orderedQuery->orderByDesc('product_variants.created_at');
    }

    // Manual pagination on ordered query
    $totalMatching = count($matchingIds);
    $offset = ($page - 1) * $perPage;
    $orderedItems = $orderedQuery->skip($offset)->take($perPage)->get();

    // Convert to ProductResource (no change to the resource)
    $products = ProductResource::collection($orderedItems)->resolve();

    // Price range and facets calculated from original variantQuery context
    $priceRange = $this->computePriceRangeForQuery((clone $variantQuery));
    $facets = $this->computeFacetsForQuery($variantQuery, $attributesInput, $this->facetSampleLimit, $this->facetCacheTtl);

    $lastPage = (int) ceil($totalMatching / $perPage);

    return response()->json([
      'success' => true,
      'payload' => [
        'data' => $products,
        'meta' => [
          'total' => $totalMatching,
          'per_page' => $perPage,
          'current_page' => $page,
          'last_page' => $lastPage,
        ],
        'price_range' => $priceRange,
        'facets' => $facets,
        'selected_filters' => $attributesInput
      ]
    ]);
  }

  /**
   * Apply attribute filters to the query.
   * Supports attributes by name: attributes[Color][]=Red
   * and attributes by id:   attributes[13][]=21
   *
   * Returns array of selected attribute_value_ids (ints).
   */
  protected function applyAttributeFiltersToQuery($variantQuery, array $attributesInput): array
  {
    $selectedValueIds = [];

    if (empty($attributesInput)) return $selectedValueIds;

    foreach ($attributesInput as $attrKey => $values) {
      if (is_numeric($attrKey)) {
        // numeric attribute id -> values are value ids
        $attrId = (int)$attrKey;
        $valueIds = array_map('intval', (array)$values);
        if (empty($valueIds)) continue;

        $variantQuery->whereHas('variantAttributes', function ($q) use ($attrId, $valueIds) {
          $q->where('attribute_id', $attrId)->whereIn('attribute_value_id', $valueIds);
        });

        $selectedValueIds = array_merge($selectedValueIds, $valueIds);
      } else {
        // attrKey is attribute name (string) -> values are attribute value strings
        $attrName = trim($attrKey);
        $valueStrings = array_map('trim', (array)$values);
        if (empty($valueStrings)) continue;

        // resolve to attribute id and value ids
        $attribute = \App\Models\ProductAttribute::where('name', $attrName)->first();
        if (! $attribute) continue;
        $attrId = $attribute->id;
        $valueRows = $attribute->values()->whereIn('value', $valueStrings)->get();
        $valueIds = $valueRows->pluck('id')->toArray();
        if (empty($valueIds)) continue;

        $variantQuery->whereHas('variantAttributes', function ($q) use ($attrId, $valueIds) {
          $q->where('attribute_id', $attrId)->whereIn('attribute_value_id', $valueIds);
        });

        $selectedValueIds = array_merge($selectedValueIds, $valueIds);
      }
    }

    return array_map('intval', array_values(array_unique($selectedValueIds)));
  }

  /**
   * Compute price range (min/max) for the query.
   */
  protected function computePriceRangeForQuery($query): array
  {
    $row = $query->selectRaw('MIN(COALESCE(sale_price, regular_price)) as min_price, MAX(COALESCE(sale_price, regular_price)) as max_price')->first();
    $min = (float) ($row->min_price ?? 0);
    $max = (float) ($row->max_price ?? 0);

    return [
      'minPrice' => $min,
      'maxPrice' => $max,
      'actualMinPrice' => $min,
      'actualMaxPrice' => $max
    ];
  }

  /**
   * Compute facets (attributes with values + counts).
   *
   * Strategy:
   *  - sample up to $limit variant IDs from the query
   *  - aggregate counts from product_variant_attributes grouped by attribute/value
   *  - join attribute/value meta and return counts
   *
   * $selectedAttributesInput is the original attributes param from client to mark selected.
   */
  protected function computeFacetsForQuery($variantQuery, array $selectedAttributesInput = [], int $limit = 500, int $cacheTtl = 30): array
  {
    // build cache key from normalized query + selected filters
    $cacheKey = 'facets:' . md5(json_encode(request()->query()) . '|' . $limit);

    return Cache::remember($cacheKey, $cacheTtl, function () use ($variantQuery, $selectedAttributesInput, $limit) {
      // 1) get variant IDs sample
      $variantIds = $variantQuery->limit($limit)->pluck('id')->toArray();
      if (empty($variantIds)) return [];

      // 2) aggregate counts per attribute/value
      $rows = DB::table('product_variant_attributes')
        ->select('attribute_id', 'attribute_value_id', DB::raw('COUNT(*) as cnt'))
        ->whereIn('product_variant_id', $variantIds)
        ->groupBy('attribute_id', 'attribute_value_id')
        ->get();

      if ($rows->isEmpty()) return [];

      // 3) prepare map attribute_id => [value_id => count]
      $counts = [];
      foreach ($rows as $r) {
        $counts[$r->attribute_id][$r->attribute_value_id] = (int)$r->cnt;
      }

      // 4) fetch attributes and only relevant values (preserving order)
      $attributeIds = array_keys($counts);
      $attrs = ProductAttribute::whereIn('id', $attributeIds)
        ->where('status', 1)
        ->with(['values' => function ($q) use ($counts) {
          $valueIds = [];
          foreach ($counts as $attrId => $map) {
            $valueIds = array_merge($valueIds, array_keys($map));
          }
          $valueIds = array_unique($valueIds);
          $q->whereIn('id', $valueIds)->orderBy('sequence');
        }])->orderBy('sequence')->get();

      // 5) build final facets array with counts and is_selected
      $selectedValueIds = $this->normalizeSelectedValueIds($selectedAttributesInput); // ints

      $facets = [];
      foreach ($attrs as $attr) {
        $values = [];
        foreach ($attr->values as $val) {
          $count = $counts[$attr->id][$val->id] ?? 0;
          $values[] = [
            'id' => $val->id,
            'value' => $val->value,
            'count' => $count,
            'is_selected' => in_array($val->id, $selectedValueIds),
          ];
        }
        $facets[] = [
          'attribute_id' => $attr->id,
          'attribute_name' => $attr->name,
          'values' => $values
        ];
      }

      return $facets;
    });
  }

  /**
   * Normalize selected attributes input (mix of names and ids) to a flat array of value ids.
   */
  protected function normalizeSelectedValueIds(array $attributesInput): array
  {
    $selected = [];

    foreach ($attributesInput as $attrKey => $values) {
      if (is_numeric($attrKey)) {
        foreach ((array)$values as $v) {
          $selected[] = (int)$v;
        }
      } else {
        // name-based -> resolve to attribute values
        $attribute = \App\Models\ProductAttribute::where('name', $attrKey)->first();
        if (!$attribute) continue;
        $rows = $attribute->values()->whereIn('value', (array)$values)->pluck('id')->toArray();
        $selected = array_merge($selected, $rows);
      }
    }
    return array_map('intval', array_values(array_unique($selected)));
  }
}
