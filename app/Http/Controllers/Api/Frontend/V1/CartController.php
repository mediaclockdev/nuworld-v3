<?php

namespace App\Http\Controllers\Api\Frontend\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\AddtoCartRequest;
use App\Http\Requests\Frontend\ApiProductVariantIDRequest;
use App\Http\Requests\Frontend\CheckoutRequest;
use App\Http\Requests\Frontend\MoveOrRemoveWishlistRequest;
use App\Http\Requests\Frontend\ProductVariantIDRequest;
use App\Http\Requests\Frontend\UpdateCartQuantityRequest;
use App\Http\Resources\Api\Frontend\AddressResource;
use App\Http\Resources\Api\Frontend\CartItemResource;
use App\Models\Coupon;
use App\Models\Order;
use App\Services\Frontend\CartService;
use App\Services\Frontend\CategoryService;
use App\Services\Frontend\CheckoutService;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\Support\Facades\Auth;
use Vinkla\Hashids\Facades\Hashids;
use App\Traits\PaginatesResponse;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
  use PaginatesResponse;
  public function __construct(private CartService $cartService, private CheckoutService $checkoutService) {}

  public function myCart(Request $request)
  {
    $cart_items_data = $this->cartService->getCartData();
    $address = $this->checkoutService->getCheckoutData();

    $perPage = $request->get('per_page', 10);
    $cartPage = $request->get('cart_page', 1);
    $couponCode = $request->get('coupon_code'); // passed from frontend if applied

    $cartItems = collect($cart_items_data['cart_items'] ?? []);
    $paginatedCart = $cartItems->forPage($cartPage, $perPage)->values();

    $total = 0;
    $totalTax = 0;
    $totalCategoryTaxPercent = 0;

    foreach ($cartItems as $item) {
      $variant = $item->productVariant;
      $promo = findSalePrice($variant->id);

      $unitPrice = $promo['regular_price_true'] ? $promo['regular_price'] : $promo['display_price'];
      $subtotal = $unitPrice * $item->quantity;
      $total += $subtotal;

      $categoryTaxRate = $variant?->category?->tax ?? 0;
      $itemTax = ($unitPrice * $item->quantity * $categoryTaxRate) / 100;
      $totalTax += $itemTax;
      $totalCategoryTaxPercent += $categoryTaxRate;
    }

    $avgTax = count($cartItems) > 0 ? $totalCategoryTaxPercent / count($cartItems) : 0;
    $orderAmount = $total + $totalTax;

    // -----------------------
    // Coupon Discount Logic
    // -----------------------
    $discount = 0;
    $couponInfo = null;

    if ($couponCode) {
      $coupon = Coupon::where('code', trim($couponCode))
        ->where('is_active', true)
        ->where(function ($query) {
          $query->whereNull('valid_from')->orWhere('valid_from', '<=', now());
        })
        ->where(function ($query) {
          $query->whereNull('valid_to')->orWhere('valid_to', '>=', now());
        })
        ->first();

      if ($coupon) {
        $userId = Auth::id();

        $userLimitExceeded = $coupon->per_user_limit &&
          Order::where('user_id', $userId)->where('coupon_id', $coupon->id)->count() >= $coupon->per_user_limit;

        $globalLimitExceeded = $coupon->max_uses &&
          Order::where('coupon_id', $coupon->id)->count() >= $coupon->max_uses;

        if (!$userLimitExceeded && !$globalLimitExceeded) {
          if ($orderAmount >= $coupon->min_order_value) {
            $discount = $coupon->type === 'Flat'
              ? $coupon->discount_amount
              : min($coupon->max_discount ?? PHP_INT_MAX, ($coupon->discount_amount / 100) * $orderAmount);

            if ($discount > $orderAmount) {
              $discount = 0;
            } else {
              $couponInfo = [
                'coupon_code' => strtoupper($coupon->code),
                'discount' => round($discount, 2),
                'coupon_id' => $coupon->id,
              ];
            }
          }
        }
      }
    }

    $finalAmountBeforeDelivery = max(0, $orderAmount - $discount);

    // -----------------------
    // Delivery Charges Logic
    // -----------------------
    $deliveryChargesTotal = 0;
    $deliveryChargesList = [];

    $extra_charges = DB::table('charges')->where('status', true)->get();

    foreach ($extra_charges as $charge) {
      $applyCharge = true;
      $conditions = json_decode($charge->conditions, true) ?? [];

      // Apply min_order condition
      if (isset($conditions['min_order']) && $finalAmountBeforeDelivery < $conditions['min_order']) {
        $applyCharge = false;
      }

      if (!$applyCharge) {
        continue;
      }

      $chargeAmount = 0;
      switch ($charge->calculation_method) {
        case 'fixed':
          $chargeAmount = $charge->value;
          break;
        case 'percentage':
          $chargeAmount = ($finalAmountBeforeDelivery * $charge->value) / 100;
          break;
      }

      if ($chargeAmount <= 0) {
        continue;
      }

      $deliveryChargesTotal += $chargeAmount;
      $deliveryChargesList[] = [
        'name' => $charge->name,
        'amount' => displayPrice($chargeAmount),
        'raw_amount' => displayPrice($chargeAmount),
        'method' => $charge->calculation_method,
        'value' => $charge->value,
      ];
    }

    $finalAmount = $finalAmountBeforeDelivery + $deliveryChargesTotal;

    // -----------------------
    // Final Response
    // -----------------------
    return response()->json([
      'success' => true,
      'message' => __('response.success.fetch', ['item' => 'Cart Data']),
      'data' => [
        'cart_items' => CartItemResource::collection($paginatedCart),
        'cart_summary' => [
          'cart_count' => count($cartItems),
          'subtotal' => displayPrice($total),
          'total_tax' => displayPrice($totalTax),
          'average_tax_percent' => $avgTax,
          'grand_total' => displayPrice($orderAmount),
          'discount' => displayPrice($discount),
          'delivery_charges' => displayPrice($deliveryChargesTotal),
          'final_amount' => displayPrice($finalAmount),
          'raw_subtotal' => $total,
          'raw_tax' => $totalTax,
          'raw_grand_total' => $orderAmount,
          'coupon_discount' => $discount,
          'raw_final_amount' => $finalAmount,
          'coupon_id' => $couponInfo['coupon_id'] ?? null,
        ],
        'coupon' => $couponInfo,
        'delivery_charges_breakup' => $deliveryChargesList,
        'billing_address' => !empty($address['billing_address'])
          ? (new AddressResource($address['billing_address']))->toArray(request())
          : null,

        'shipping_address' => !empty($address['shipping_address'])
          ? (new AddressResource($address['shipping_address']))->toArray(request())
          : null,
      ],
      'pagination' => $this->formatPagination($cartItems, $cartPage, $perPage)
    ], 200);
  }




  public function myWishlist(Request $request)
  {
    $perPage = $request->get('per_page', 10); // Default to 10 items per page
    $page = $request->get('page', 1);

    $cart_items_data = $this->cartService->getCartData();
    //pd($cart_items_data);

    $savedItems = collect($cart_items_data['saved_for_later_items'] ?? []);
    //pd($savedItems);
    $paginated = $savedItems->forPage($page, $perPage)->values();
    //pd($paginated);

    return response()->json([
      'success' => true,
      'message' => __('response.success.fetch', ['item' => 'Wishlist Data']),
      'data' => [
        'saved_for_later_items' => CartItemResource::collection($paginated),
        'wishlist_count' => count($savedItems),
      ],
      'pagination' => $this->formatPagination($savedItems, $page, $perPage)
    ]);
  }

  public function removeFromCart(ApiProductVariantIDRequest $request): JsonResponse
  {
    return $this->cartService->removeFromCartApi($request->product_variant_id);


    //return ApiResponse::success([], __('response.success.remove', ['item' => 'Item']));
  }

  public function removeFromWishlist(ApiProductVariantIDRequest $request): JsonResponse
  {
    return $this->cartService->removeFromWishlistApi($request->product_variant_id);


    //return ApiResponse::success([], __('response.success.remove', ['item' => 'Item']));
  }

  public function addToCart(AddtoCartRequest $request): JsonResponse
  {
    $data = $request->validated();
    return $this->cartService->addToCartApi($data);
  }

  public function addToWishlist(AddtoCartRequest $request): JsonResponse
  {
    $data = $request->validated();
    return $this->cartService->addToWishlist($data);
  }

  public function moveOrRemoveWishlist(MoveOrRemoveWishlistRequest $request): JsonResponse
  {
    $data = $request->validated();

    if ($data['action'] === 'remove') {
      return $this->cartService->removeFromWishlistApi($data['product_variant_id']);
    }

    return $this->cartService->addToWishlist([
      'product_variant_id' => $data['product_variant_id'],
      'quantity' => $data['quantity'],
      'is_saved_for_later' => true,
    ]);
  }

  public function updateQuantity(UpdateCartQuantityRequest $request): JsonResponse
  {
    //dd($request->product_variant_id, $request->quantity);
    return $this->cartService->updateQuantityApi($request->product_variant_id, $request->quantity);
  }
}
