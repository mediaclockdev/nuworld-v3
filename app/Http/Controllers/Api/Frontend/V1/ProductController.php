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

  public function searchByProductItems(Request $request)
  {
    ifApiTokenExists();
    $productID = Hashids::decode($request->product_id)[0] ?? null;
    //pd($productID);
    // if (!$productID)
    //   return ApiResponse::error(__('response.not_found', ['item' => 'Product']), 404);
    $variants = ProductVariant::with('product', 'category') //->where('status', 1)
      ->where('product_id', $productID)
      ->get();
    // ->groupBy('category_id')
    // ->map(function ($group) {
    //   return [
    //     'category_name'  => $group->first()->category->name ?? '',
    //     'category_image' => $group->first()->category->image ?? '',
    //     'variant_count'  => $group->count()
    //   ];
    // })
    // ->values();
    pd($variants);

    //return ApiResponse::success($variants, __('response.success.fetch', ['item' => 'Collections Found !!']));
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
    $orderedImages = $productVariant->images->sortByDesc('is_default');

    // --------- Attribute / Color Options (same logic as web show) ----------
    // Expecting getAttributeOptions to return ['attributes' => ..., 'combinations' => ...]
    $attributeOptionsData = $this->productService->getAttributeOptions($productVariant);
    $attributeOptions = $attributeOptionsData['attributes'] ?? [];
    $combinations = $attributeOptionsData['combinations'] ?? [];

    // If you only want color options (for example attribute_id or attribute_name === 'color'),
    // filter $attributeOptions accordingly. Example assuming attribute has 'name' key:
    // $colorOptions = collect($attributeOptions)->filter(fn($a) => strtolower($a['name'] ?? '') === 'color')->values();
    // But for now return all attribute options and let frontend decide.
    $colorOptions = $attributeOptions;

    // ------------------ Handle Reviews ------------------
    $productReviews = collect();

    if (auth()->check()) {
      $user = auth()->user();
      $userId = $user->id;

      $hasCompletedOrder = $user->hasCompletedOrderForVariant($productVariant->id); // optional to return

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

    // Checkout more products (keep as you had it)
    $checkoutProducts = ProductVariant::where('status', 1)
      ->where('product_id', '!=', $productVariant->product_id)
      ->take(3)
      ->get();

    // Build response data, include attribute options + combinations
    $data = [
      'product' => ProductDetailsResource::make($productVariant),
      'checkout_more_products' => ProductResource::collection($checkoutProducts),
      'color_options' => ColorOptionResource::collection(collect($colorOptions)),
      'attribute_options' => $attributeOptions,     // optional, if you want raw attributes too
      'combinations' => $combinations,              // optional
      'images' => ProductImageResource::collection($orderedImages),
      'reviews' => ProductReviewResource::collection($productReviews),
      'pincodeData' => $pincodeData,
    ];

    return ApiResponse::success($data, __('response.success.fetch', ['item' => 'Product']));
  }

  public function searchProduct()
  {
    if (!request()->has('q'))
      return ApiResponse::error(__('validation.required', ['attribute' => 'Keyword']), 404);
    ifApiTokenExists();
    $products = $this->productService->productSearch();
    return ApiResponse::success($products, __('response.success.fetch', ['item' => 'Product']));
  }

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
}
