<?php

namespace App\Services\Frontend;

use App\Models\{Address, Cart, Coupon, CustomerReward, Inventory, Order, OrderProduct, PaymentSettings, SiteSetting, State, User};
use App\Services\Frontend\CartService;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\{Collection, Facades\Auth, Str};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Vinkla\Hashids\Facades\Hashids;
use App\Services\Frontend\PaypalClient;
use Stripe\Stripe;
use Stripe\Charge;

use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalHttp\HttpException;

class CheckoutService
{
  protected $cartService;

  public function __construct(CartService $cartService)
  {
    $this->cartService = $cartService;
  }

  public function getCheckoutData(): array
  {
    $data['title']     = 'Checkout';
    $user              = Auth::user();
    $addresses         = $this->getAddress($user);
    $data              = $this->cartService->getCartData();
    $data['addresses'] = $addresses;

    // Get session-stored IDs if available
    $selectedShippingId = session('selected_shipping_address');
    $selectedBillingId  = session('selected_billing_address');

    $data['shipping_address'] = $selectedShippingId ? $addresses->firstWhere('id', $selectedShippingId) : ($addresses->firstWhere('primary', 1) ?? $addresses->firstWhere('type', 0));
    $data['billing_address']  = $selectedBillingId ? $addresses->firstWhere('id', $selectedBillingId) : ($addresses->firstWhere('primary', 1) ?? $addresses->firstWhere('type', 1));
    return $data;
  }

  public function applyCouponBkp(array $data): JsonResponse
  {
    //pd($data);

    $coupon = Coupon::where('code', trim($data['coupon_code']))
      ->where('is_active', true)
      ->where(function ($query) {
        $query->whereNull('valid_from')->orWhere('valid_from', '<=', now());
      })
      ->where(function ($query) {
        $query->whereNull('valid_to')->orWhere('valid_to', '>=', now());
      })
      ->first();

    if (!$coupon) {
      return response()->json(['success' => false, 'message' => 'Invalid or expired coupon code.']);
    }

    if ($data['order_amount'] < $coupon->min_order_value) {
      return response()->json([
        'success' => false,
        'message' => 'Minimum order amount should be ₹' . number_format($coupon->min_order_value, 2),
      ]);
    }

    $userId = Auth::id();
    if ($coupon->per_user_limit && Order::where('user_id', $userId)->where('coupon_id', $coupon->id)->count() >= $coupon->per_user_limit) {
      return response()->json(['success' => false, 'message' => 'You have already used this coupon.']);
    }

    if ($coupon->max_uses && Order::where('coupon_id', $coupon->id)->count() >= $coupon->max_uses) {
      return response()->json(['success' => false, 'message' => 'Coupon usage limit reached.']);
    }

    $discount = $coupon->type === 'Flat'
      ? $coupon->discount_amount
      : min($coupon->max_discount ?? PHP_INT_MAX, ($coupon->discount_amount / 100) * $data['order_amount']);

    if ($discount > $data['order_amount']) {
      return response()->json(['success' => false, 'message' => 'Discount cannot exceed the order amount.']);
    }

    session(['coupon_id' => $coupon->id, 'coupon.code' => $coupon->code, 'coupon.discount' => $discount]);

    return response()->json([
      'success' => true,
      'message' => 'Coupon applied successfully.',
      'html' => view('frontend.includes.coupon-discount', [
        'couponCode' => strtoupper($coupon->code),
        'discount' => round($discount, 2),
      ])->render(),
      'discount' => round($discount, 2),
      'final_amount' => displayPrice(max(0, $data['order_amount'] - $discount)),
      'coupon_id' => $coupon->id,
    ]);
  }
  public function applyCoupon(array $data): JsonResponse
  {
    $userId = Auth::id();
    $code   = trim($data['coupon_code']);

    // -------------------------
    // 1. Check Normal Coupon
    // -------------------------
    $coupon = Coupon::where('code', $code)
      ->where('is_active', true)
      ->where(function ($query) {
        $query->whereNull('valid_from')->orWhere('valid_from', '<=', now());
      })
      ->where(function ($query) {
        $query->whereNull('valid_to')->orWhere('valid_to', '>=', now());
      })
      ->first();

    if ($coupon) {
      // Min order value check
      if ($data['order_amount'] < $coupon->min_order_value) {
        return response()->json([
          'success' => false,
          'message' => 'Minimum order amount should be ₹' . number_format($coupon->min_order_value, 2),
        ]);
      }

      // Per-user limit check
      if (
        $coupon->per_user_limit &&
        Order::where('user_id', $userId)->where('coupon_id', $coupon->id)->where('order_status', 1)->count() >= $coupon->per_user_limit
      ) {
        return response()->json(['success' => false, 'message' => 'You have already used this coupon.']);
      }

      // Global limit check
      if (
        $coupon->max_uses &&
        Order::where('coupon_id', $coupon->id)->where('order_status', 1)->count() >= $coupon->max_uses
      ) {
        return response()->json(['success' => false, 'message' => 'Coupon usage limit reached.']);
      }

      // Calculate discount
      $discount = $coupon->type === 'Flat'
        ? $coupon->discount_amount
        : min($coupon->max_discount ?? PHP_INT_MAX, ($coupon->discount_amount / 100) * $data['order_amount']);

      if ($discount > $data['order_amount']) {
        return response()->json(['success' => false, 'message' => 'Discount cannot exceed the order amount.']);
      }

      session([
        'coupon_id' => $coupon->id,
        'coupon.code' => $coupon->code,
        'coupon.discount' => $discount
      ]);

      return response()->json([
        'success' => true,
        'message' => 'Coupon applied successfully.',
        'html' => view('frontend.includes.coupon-discount', [
          'couponCode' => strtoupper($coupon->code),
          'discount' => round($discount, 2),
        ])->render(),
        'discount' => round($discount, 2),
        'final_amount' => displayPrice(max(0, $data['order_amount'] - $discount)),
        'coupon_id' => $coupon->id,
      ]);
    }

    // -------------------------
    // 2. Check Scratch Card Reward (type always = coupon)
    // -------------------------
    $customerReward = CustomerReward::with('scratchCardReward')
      ->where('customer_id', $userId)
      ->where('scratch_card_code', $code)
      // ->where('status', 1) // Active
      ->where('expiry_date', '>=', now())
      ->first();

    if ($customerReward && $customerReward->scratchCardReward) {
      $reward = $customerReward->scratchCardReward;

      if ($reward->status != 1) {
        return response()->json(['success' => false, 'message' => 'Scratch card is inactive.']);
      }

      /* if (Order::where('user_id', $userId)->where('coupon_id', $reward->id)->count() > 0) {
        return response()->json(['success' => false, 'message' => 'You have already used this coupon.']);
      } */
      if ($customerReward->status == 2) {
        return response()->json(['success' => false, 'message' => 'You have already used this coupon.']);
      }

      // Since type is always 'coupon', treat value as flat discount
      $discount = $reward->value;

      if ($discount > $data['order_amount']) {
        $discount = $data['order_amount'];
      }

      // Save in session
      session([
        'scratch_card_id' => $reward->id,
        'reward.scratch_card_code' => $customerReward->scratch_card_code,
        'reward.scratch_card_discount' => $discount,
        'customer_reward_id' => $customerReward->id
      ]);


      return response()->json([
        'success' => true,
        'message' => 'Scratch card applied successfully.',
        'html' => view('frontend.includes.coupon-discount', [
          'couponCode' => strtoupper($customerReward->scratch_card_code),
          'discount' => round($discount, 2),
        ])->render(),
        'discount' => round($discount, 2),
        'final_amount' => displayPrice(max(0, $data['order_amount'] - $discount)),
        'scratch_card_id' => $reward->id,
      ]);
    }

    // If neither found
    return response()->json(['success' => false, 'message' => 'Invalid or expired coupon.']);
  }



  public function applyCouponApi(array $data): JsonResponse
  {
    $coupon = Coupon::where('code', trim($data['coupon_code']))
      ->where('is_active', true)
      ->where(function ($query) {
        $query->whereNull('valid_from')->orWhere('valid_from', '<=', now());
      })
      ->where(function ($query) {
        $query->whereNull('valid_to')->orWhere('valid_to', '>=', now());
      })
      ->first();

    if (!$coupon) {
      return response()->json([
        'success' => false,
        'message' => 'Invalid or expired coupon code.',
      ], 422);
    }

    if ($data['order_amount'] < $coupon->min_order_value) {
      return response()->json([
        'success' => false,
        'message' => 'Minimum order amount should be ₹' . number_format($coupon->min_order_value, 2),
      ], 422);
    }

    $userId = Auth::id(); // or Auth::guard('api')->id() if you're using a token-based API

    if (
      $coupon->per_user_limit &&
      Order::where('user_id', $userId)->where('coupon_id', $coupon->id)->count() >= $coupon->per_user_limit
    ) {
      return response()->json([
        'success' => false,
        'message' => 'You have already used this coupon.',
      ], 422);
    }

    if (
      $coupon->max_uses &&
      Order::where('coupon_id', $coupon->id)->count() >= $coupon->max_uses
    ) {
      return response()->json([
        'success' => false,
        'message' => 'Coupon usage limit reached.',
      ], 422);
    }

    $discount = $coupon->type === 'Flat'
      ? $coupon->discount_amount
      : min($coupon->max_discount ?? PHP_INT_MAX, ($coupon->discount_amount / 100) * $data['order_amount']);

    if ($discount > $data['order_amount']) {
      return response()->json([
        'success' => false,
        'message' => 'Discount cannot exceed the order amount.',
      ], 422);
    }

    return response()->json([
      'success' => true,
      'data' => [
        'coupon_code' => strtoupper($coupon->code),
        'discount' => round($discount, 2),
        'final_amount' => round(max(0, $data['order_amount'] - $discount), 2),
        'coupon_id' => $coupon->id,
      ],
      'message' => 'Coupon applied successfully.',

    ]);
  }

  public function removeCoupon(): JsonResponse
  {
    if (session('coupon_id')) {
      session()->forget(['coupon_id', 'coupon.code', 'coupon.discount']);
    }

    session()->forget(['coupon_id', 'coupon.code', 'coupon.discount']);
    if (session('scratch_card_id')) {
      session()->forget(['scratch_card_id', 'reward.scratch_card_code', 'reward.scratch_card_discount', 'customer_reward_id']);
    }
    return response()->json(['success' => true, 'message' => 'Coupon removed successfully.', 'html' => '']);
  }

  public function removeCouponApi(): JsonResponse
  {
    return response()->json([
      'success' => true,
      'message' => 'Coupon removed successfully.',
      'discount' => 0,
      'final_amount' => null // or recalculate without coupon if needed
    ]);
  }

  public function processCheckout(Request $request): JsonResponse
  {
    $paymentMethod = Hashids::decode(substr($request->payment_method, 1))[0] ?? null; // remove the 'P' added at the start

    $cartItems = Cart::with('productVariant')
      ->where([['user_id', user()->id], ['is_saved_for_later', false]])
      ->get();

    $this->validateCart($cartItems);

    // Check if a coupon exists in session
    $couponId = session('coupon_id');
    $couponDiscount = session('coupon.discount', 0);
    $couponType = null;

    // If no coupon, check for reward/scratch card
    if ($couponId) {
      $couponType = 1; // Normal Coupon
    } elseif ($scratchId = session('scratch_card_id')) {
      $couponId = $scratchId;
      $couponDiscount = session('reward.scratch_card_discount', 0);
      $couponType = 2; // Scratch card reward
    }
    // $order = $this->createOrder($cartItems, $request->shipping_address, $request->billing_address, $paymentMethod, session('coupon_id'), session('coupon.discount', 0));
    if (in_array($paymentMethod, [1, 2])) {
      $paymentTypeInsert = 1; // For Online Payment
    } else {
      $paymentTypeInsert = 0; // For Cash on Delivery
    }
    $order = $this->createOrder($cartItems, $request->shipping_address, $request->billing_address, $paymentTypeInsert, $couponId, $couponDiscount, $couponType);

    if (!$order)
      return response()->json([
        'success' => false,
        'message' => __('response.otp.error.create', ['item' => 'Order'])
      ], 422);

    if ($paymentMethod === 1) {
      $type = 'stripe_payment';
    } else if ($paymentMethod === 2) {
      $type = 'paypal_payment';
    } else {
      $type = 'confirm';
    }


    return response()->json([
      'success' => true,
      'redirect' => route('checkout.next-process', [
        'type' => $type,
        'order_number' => $order->order_number
      ]),
      'message' => __('response.success.create', ['item' => 'Order'])
    ]);
  }

  public function processApiCheckout(Request $request): JsonResponse
  {
    //pd($request->all());
    $user = $request->user(); // Get authenticated user via token

    // Decode payment method (e.g., 'P3' => 3)
    //$paymentMethod = Hashids::decode(substr($request->payment_method, 1))[0] ?? null;
    // $paymentMethod = Hashids::decode($request->payment_method)[0] ?? null;
    // $method = PaymentSettings::where('id', $paymentMethod)->first();
    $paymentMethod = $request->payment_method ?? null;
    //pd($paymentMethod);
    // if (!$paymentMethod) {
    //   return response()->json([
    //     'success' => false,
    //     'data' => [],
    //     'message' => __('validation.invalid', ['attribute' => 'payment method']),
    //   ], 422);
    // }

    // if (!$paymentMethod) {
    //   return response()->json([
    //     'success' => false,
    //     'message' => __('validation.invalid', ['attribute' => 'payment method']),
    //   ], 422);
    // }

    // Retrieve cart items for the user
    $cartItems = Cart::with('productVariant')
      ->where([
        ['user_id', $user->id],
        ['is_saved_for_later', false]
      ])
      ->get();

    // Validate cart
    $this->validateCart($cartItems);

    // Optional: Replace session() with API-compatible coupon handling (pass via request)
    $couponId = $request->input('coupon_id'); // optional
    $couponDiscount = $request->input('coupon_discount', 0); // optional

    // Create the order
    $order = $this->createOrder(
      $cartItems,
      $request->shipping_address,
      $request->billing_address,
      $paymentMethod,
      $couponId,
      $couponDiscount,
      $couponId ? 1 : null // Coupon Type
    );

    if (!$order) {
      return response()->json([
        'success' => false,
        'data' => [],
        'message' => __('response.otp.error.create', ['item' => 'Order']),
      ], 422);
    }

    return response()->json([
      'success'      => true,
      'data' => [
        'order_number' => $order->order_number,
        'order_type'   => $paymentMethod === 1 ? 'payment' : 'confirm',
        'next_step'   => $paymentMethod === 1 ? 'stripe_payment' : 'confirm',
      ],
      'message'      => __('response.success.create', ['item' => 'Order'])
    ]);
  }



  public function changeDefaultAddress(int $addressId, ?string $type): JsonResponse
  {
    $userId = Auth::id();
    $address = Address::where('id', $addressId)->where('user_id', $userId)->first();

    if (!$address) {
      return response()->json(['success' => false, 'message' => 'Address not found.']);
    }

    if ($type === null) {
      Address::where('user_id', $userId)->where('id', '!=', $addressId)->update(['primary' => 0]);
      $address->update(['primary' => 1]);
    } else {
      Address::where('user_id', $userId)->where('id', '!=', $addressId)->update(['type' => 0]);
      $address->update(['type' => $type]);
    }

    return response()->json(['success' => true, 'message' => 'Default address changed successfully.']);
  }

  protected function validateCart($cartItems): void
  {
    if ($cartItems->isEmpty()) {
      throw new \Exception('Your cart is empty');
    }

    foreach ($cartItems as $item) {
      if (!$item->productVariant) {
        throw new \Exception('One or more products are no longer available');
      }

      $inventory = Inventory::where('product_variant_id', $item->product_variant_id)->lockForUpdate()->first();

      if (!$inventory) {
        throw new \Exception('Inventory not found for a product variant');
      }

      if ($inventory->quantity < $item->quantity) {
        throw new \Exception("Insufficient stock for product: {$item->productVariant->name}. Consider removing the product from the cart or transferring it from the wishlist to proceed with checkout.");
      }
    }
  }

  protected function createOrder($cartItems, int $shippingId, int $billingId, string $paymentMethod, ?int $couponId, float $couponDiscount, ?int $couponType): Order
  {
    $userId = user()->id;

    $existingOrder = $this->getPendingOrderForUser(user()->id);

    if ($existingOrder && $this->isCartSameAsOrder($existingOrder, $cartItems->toArray())) {

      $totals = $this->calculateTotals($cartItems, $couponDiscount);

      $existingOrder->update([
        'payment_method'  => $paymentMethod,
        'coupon_id'       => $couponId,
        'coupon_type'     => $couponType,
        'coupon_discount' => $couponDiscount,
        'order_total'     => $totals['subtotal'],
        'net_total'       => $totals['total'],
        'total_tax'       => $totals['total_tax'],
        'other_charges'   => json_encode($totals['other_charges']),
      ]);

      return $existingOrder;
    }


    $totals = $this->calculateTotals($cartItems, $couponDiscount);

    $order = Order::create([
      'user_id'          => $userId,
      'order_number'     => generateOrderNumber(),
      'payment_method'   => $paymentMethod,
      'payment_status'   => 0,
      'order_status'     => 0,
      'order_total'      => $totals['subtotal'],
      // 'shipping_charge'  => $totals['shipping'],
      'coupon_type'      => $couponType,
      'coupon_id'        => $couponId,
      'coupon_discount'  => $couponDiscount,
      'net_total'        => $totals['total'],
      'total_tax'        => $totals['total_tax'],
      'other_charges'        => json_encode($totals['other_charges']),
      'shipping_address' => $this->getAddressById($userId, $shippingId),
      'billing_address'  => $this->getAddressById($userId, $billingId),
      'created_by'       => $userId
    ]);

    $this->createOrderProducts($order, $cartItems);

    return $order;
  }

  protected function calculateTotals($cartItems, $couponDiscount): array
  {
    $subtotal = 0;
    $totalTax = 0;
    $totalCategoryTaxPercent = 0;

    foreach ($cartItems as $item) {
      $variant = $item->productVariant;
      $promo = findSalePrice($variant->id);
      $price = $promo['regular_price_true'] ? $promo['regular_price'] : $promo['display_price'];

      $subtotal += $price * $item->quantity;

      $categoryTaxRate = optional($variant->category)->tax ?? 0;
      $totalTax += $this->calculateTax($price, $item->quantity, $categoryTaxRate);
      $totalCategoryTaxPercent += $categoryTaxRate;
    }

    $grandTotal = $subtotal + $totalTax;

    // ==== Delivery Charges JSON ====
    $deliveryCharges = [];
    $charges = DB::table('charges')->where('status', true)->get();

    foreach ($charges as $charge) {
      $applyCharge = true;
      $conditions = json_decode($charge->conditions, true) ?? [];

      if (isset($conditions['min_order']) && $grandTotal < $conditions['min_order']) {
        $applyCharge = false;
      }

      if (!$applyCharge) continue;

      $chargeAmount = 0;

      switch ($charge->calculation_method) {
        case 'fixed':
          $chargeAmount = $charge->value;
          break;
        case 'percentage':
          $chargeAmount = ($grandTotal * $charge->value) / 100;
          break;
        case 'weight_based':
          $totalWeight = $cartItems->sum(fn($item) => $item->productVariant->weight * $item->quantity);
          $chargeAmount = $totalWeight * $charge->value;
          break;
        case 'distance_based':
          $distanceInKm = session('distance_km', 0);
          $chargeAmount = $distanceInKm * $charge->value;
          break;
      }

      $deliveryCharges[Str::slug($charge->name, '_')] = $chargeAmount;
    }

    $totalDeliveryCharges = array_sum($deliveryCharges);

    // Final total calculation
    $total = max($subtotal + $totalTax + $totalDeliveryCharges - $couponDiscount, 0);

    return [
      'subtotal' => $subtotal,
      'total_tax' => $totalTax,
      'discount' => $couponDiscount,
      'other_charges' => $deliveryCharges, // JSON-formatted array
      'delivery_charges_total' => $totalDeliveryCharges,
      'total' => $total,
    ];
  }


  protected function calculateTax(float $price, int $quantity, float $taxRate): float
  {
    return ($price * $quantity) * ($taxRate / 100);
  }

  public function getAddressById(int $userID, $addressId): string
  {
    $address = Address::where([['user_id', $userID], ['id', $addressId]])->first();

    return json_encode($this->formatedAddress($address));
  }

  public function getAddress(User $user): Collection
  {
    return $user->addresses()->get();
  }

  protected function createOrderProducts(Order $order, $cartItems): void
  {
    foreach ($cartItems as $item) {
      $inventory = Inventory::where('product_variant_id', $item->product_variant_id)->first();

      if (!$inventory) {
        throw new \Exception('Inventory not found for product variant ID: ' . $item->product_variant_id);
      }

      $variant = $item->productVariant;
      $promo = findSalePrice($variant->id);
      $sellPrice = $promo['regular_price_true'] ? $promo['regular_price'] : $promo['display_price'];
      $promotionId = $promo['regular_price_true'] ? null : $promo['promotion_id'] ?? null;

      OrderProduct::create([
        'order_id' => $order->id,
        'order_item_uid' => Str::uuid(),
        'variant_id' => $item->product_variant_id,
        'sku' => $variant->sku,
        'quantity' => $item->quantity,
        'promotion_id' => $promotionId,
        'regular_price' => $promo['regular_price'],
        'sell_price' => $sellPrice,
        'tax_amount' => $this->calculateTax($sellPrice, $item->quantity, optional($variant->category)->tax ?? 0),
        'status' => 0,
        'created_by' => $order->user_id,
      ]);

      /* $inventory->decrement('quantity', $item->quantity);
      if ($inventory->quantity <= $inventory->threshold) {
        $this->alertAdmin($inventory);
      } */
    }
  }

  protected function formatedAddress($address): array
  {
    if (!$address) {
      return [
        'name'    => '',
        'address' => '',
        'state'   => '',
        'phone'   => '',
      ];
    }

    return [
      'name' => $address->name ?? '',
      'address' => implode(', ', array_filter(
        [
          $address->address_1 ?? '',
          $address->address_2 ?? '',
          $address->city ?? '',
          $address->landmark ?? '',
          optional($address->state)->name ?? '',
          optional(optional($address->state)->country)->name ?? '',
          $address->pin ?? ''
        ],
        fn($value) => !is_null($value) && trim($value) !== ''
      )),
      'state' => optional($address->state)->name ?? '',
      'phone' => $address->phone ?? '',
    ];
  }


  protected function alertAdmin(Inventory $inventory): void
  {
    app('EmailService')->sendEmail(
      adminMailsByRoleID([SiteSetting::where('key', 'threshold_mails_id')->value('value') ?? 1])[0],
      'Product threshold reached - ' . $inventory->variant->name,
      'emails.threshold-mail',
      ['inventory' => $inventory],
      array_slice(adminMailsByRoleID([SiteSetting::where('key', 'threshold_mails_id')->value('value') ?? 1]), 1),
      []
    );
  }

  public function updateOrderPayment($order_number, $paymentMethod = 'COD', $paymentDetails): JsonResponse
  {
    $order = Order::where('order_number', $order_number)->firstOrFail();

    $order->orderHistories()->create([
      'scheduled_date' => $order->created_at->format('Y-m-d'),
      'scheduled_time' => $order->created_at->format('H:i:s'),
      'description' => '',
      'status' => 1,
    ]);

    $paymentDetailsArray = [];
    if ($paymentMethod === 'COD') {
      $paymentDetailsArray = [];
    } elseif ($paymentMethod === 'Stripe') {
      $paymentDetailsArray = [
        'transaction_id' => $paymentDetails->id,
        'created_at' => date("Y-m-d H:i:s", substr($paymentDetails->created, 0, 10)) ?? null,
        'receipt_url' => $paymentDetails->receipt_url ?? null,
        'amount' => ($paymentDetails->amount ?? 0) / 100,
        'currency' => $paymentDetails->currency ?? null,
        'failure_message' => $paymentDetails->failure_message ?? null,
        'failure_code' => $paymentDetails->failure_code ?? null,
        'status' => $paymentDetails->status ?? null,
      ];
    } elseif ($paymentMethod === 'PayPal') {
      $capture = $paymentDetails->purchase_units[0]->payments->captures[0] ?? null;
      if (!$capture) {
        throw new \Exception('PayPal capture details not found');
      }
      $paymentDetailsArray = [
        'transaction_id' => $capture->id ?? null,
        'created_at' => $capture->create_time ? date("Y-m-d H:i:s", strtotime($capture->create_time)) : null,
        'receipt_url' => null, // PayPal doesn't provide a direct receipt_url
        'amount' => $capture->amount->value ?? 0,
        'currency' => $capture->amount->currency_code ?? null,
        'failure_message' => $capture->status === 'DECLINED' ? ($capture->status_details->reason ?? null) : null,
        'failure_code' => null, // PayPal doesn't use failure_code like Stripe
        'status' => $capture->status ?? null,
      ];
    }

    if ($paymentMethod === 'Stripe' || $paymentMethod === 'PayPal') {
      $type = 1;
    } else {
      $type = 0;
    }

    $order->update([
      'payment_method' => $type,
      'payment_status' => 1,
      'payment_details' => json_encode($paymentDetailsArray),
      'order_status' => 1,
      'transaction_id' => $paymentMethod !== 'COD' ? ($paymentDetailsArray['transaction_id'] ?? null) : null,
    ]);
    // UPDATE INVENTORY
    $cartItems = Cart::with('productVariant')
      ->where([['user_id', user()->id], ['is_saved_for_later', false]])
      ->get();

    foreach ($cartItems as $item) {
      $inventory = Inventory::where('product_variant_id', $item->product_variant_id)->first();

      if (!$inventory) {
        throw new \Exception('Inventory not found for product variant ID: ' . $item->product_variant_id);
      }

      $inventory->decrement('quantity', $item->quantity);
      if ($inventory->quantity <= $inventory->threshold) {
        $this->alertAdmin($inventory);
      }
    }

    $this->cartService->clearCart();

    app('EmailService')->sendEmail(
      $order->user->email,
      "Your Order Confirmation - #{$order->order_number}",
      'emails.frontend.order-confirmation',
      ['user' => $order->user, 'order' => $order],
      [],
      adminMailsByRoleID([SiteSetting::where('key', 'order_copy_to_id')->value('value') ?? 1])
    );

    return response()->json(['success' => true,  'data' => [
      'order_number' => $order->order_number,
      'discount' => displayPrice($order->coupon_discount),
    ], 'message' => __('response.success.generate', ['item' => 'Order']), 'redirect' => route('order.confirmation', ['order_number' => $order->order_number])]);
  }

  // public function updateOrderPayment($order_number, $paymentMethod = 'COD', $paymentDetails): JsonResponse
  // {
  //   $order = Order::where('order_number', $order_number)->firstOrFail();

  //   $order->orderHistories()->create([
  //     'scheduled_date' => $order->created_at->format('Y-m-d'),
  //     'scheduled_time' => $order->created_at->format('H:i:s'),
  //     'description' => '',
  //     'status' => 1,
  //   ]);

  //   if ($paymentMethod !== 'COD')
  //     $paymentDetailsArray = [
  //       'transaction_id' => $paymentDetails->id,
  //       'created_at' => date("Y-m-d H:i:s", substr($paymentDetails->created, 0, 10)) ?? null,
  //       'receipt_url' => $paymentDetails->receipt_url ?? null,
  //       'amount' => ($paymentDetails->amount ?? 0) / 100,
  //       'currency' => $paymentDetails->currency ?? null,
  //       'failure_message' => $paymentDetails->failure_message ?? null,
  //       'failure_code' => $paymentDetails->failure_code ?? null,
  //       'status' => $paymentDetails->status ?? null
  //     ];

  //   $order->update([
  //     'payment_method' => $paymentMethod === 'COD' ? 0 : 1,
  //     'payment_status' => 1,
  //     'payment_details' => json_encode($paymentDetailsArray ?? []),
  //     'order_status' => 1,
  //     'transaction_id' => $paymentMethod !== 'COD'
  //       ? ($paymentDetailsArray['transaction_id'] ?? null)
  //       : null,
  //   ]);

  //   $this->cartService->clearCart();

  //   app('EmailService')->sendEmail(
  //     $order->user->email,
  //     "Your Order Confirmation - #{$order->order_number}",
  //     'emails.frontend.order-confirmation',
  //     ['user' => $order->user, 'order' => $order],
  //     [],
  //     adminMailsByRoleID([SiteSetting::where('key', 'order_copy_to_id')->value('value') ?? 1])
  //   );

  //   return response()->json(['success' => true, 'order_number' => $order->order_number, 'message' => __('response.success.generate', ['item' => 'Order']), 'redirect' => route('order.confirmation', ['order_number' => $order->order_number])]);
  // }

  protected function getPendingOrderForUser($userId): ?Order
  {
    return Order::where([['user_id', $userId], ['payment_status', 0], ['order_status', 0]])
      ->latest('id')
      ->first();
  }

  protected function isCartSameAsOrder(Order $order, array $cartItems): bool
  {
    return collect($order->orderProducts)->pluck('variant_id')->all() === collect($cartItems)->pluck('product_variant_id')->all();
  }

  public function stripeInitiatePaymen1($order_number): array
  {
    $order = Order::where('order_number', $order_number)->firstOrFail();
    $stripePublicKey = PaymentSettings::where([['gateway_name', 'stripe'], ['gateway_mode', 'test']])->value('gateway_key');

    return ['stripePublicKey' => $stripePublicKey, 'order' => $order->toArray()];
  }

  public function stripeInitiatePayment($order_number): array
  {
    $order = Order::where('order_number', $order_number)->firstOrFail();
    $stripePublicKey = PaymentSettings::where([['gateway_name', 'stripe'], ['gateway_mode', 'test']])->value('gateway_key');

    return ['stripePublicKey' => $stripePublicKey, 'order' => $order->toArray()];
  }

  public function stripeProcessPayment(Request $request): JsonResponse
  {
    $stripeSecretKey = PaymentSettings::where([['gateway_name', 'stripe'], ['gateway_mode', 'test']])->value('gateway_secret');
    Stripe::setApiKey($stripeSecretKey);
    $netAmount = Order::where('order_number', $request->order_number)->value('net_total');
    $currencyCode = explode('~', displayPrice(1, true))[1];

    try {
      $charge = Charge::create([
        'amount' => $netAmount * 100,
        'currency' => $currencyCode,
        'source' => $request->stripeToken,
        'description' => 'Payment for order',
      ]);

      $this->updateOrderPayment($request->order_number, 'Stripe', $charge);
      return response()->json([
        'success' => true,
        'message' => 'Payment successful.',
        'order_number' => $request->order_number,
      ]);
    } catch (\Stripe\Exception\CardException $e) {
      $errorMessage = $e->getMessage();
      $declineCode = $e->getError()->decline_code ?? 'none';
      $stripeCode = $e->getStripeCode();
      Order::where('order_number', $request->order_number)->update([
        'payment_status' => 2,
        'payment_failure_reason' => "Stripe Error: {$errorMessage} | Decline: {$declineCode} | Code: {$stripeCode}",
      ]);

      return response()->json([
        'success' => false,
        'message' => $errorMessage,
        'order_number' => $request->order_number,
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
        'order_number' => $request->order_number,
      ]);
    }
  }



  public function paypalInitiatePayment($order_number): array
  {
    $order = Order::where('order_number', $order_number)->firstOrFail();
    $paypalClientId = PaymentSettings::select('gateway_key', 'gateway_mode')->where([['gateway_name', 'paypal'], ['gateway_mode', 'sandbox']])->first();
    if ($paypalClientId['gateway_mode'] == 'sandbox') {
      $currencyCode = 'USD';
    } else {
      $currencyCode = explode('~', displayPrice(1, true))[1];
    }

    return ['paypalClientId' => $paypalClientId['gateway_key'], 'order' => $order->toArray(), 'currencyCode' => $currencyCode];
  }
  public function paypalCreateOrder(Request $request): JsonResponse
  {
    try {
      $order = Order::where('order_number', $request->order_number)->firstOrFail();
      $paypalClientId = PaymentSettings::select('gateway_key', 'gateway_mode')->where([['gateway_name', 'paypal'], ['gateway_mode', 'sandbox']])->first();
      if ($paypalClientId['gateway_mode'] == 'sandbox') {
        $currencyCode = 'USD';
      } else {
        $currencyCode = explode('~', displayPrice(1, true))[1];
      }

      $paypalRequest = new OrdersCreateRequest();
      $paypalRequest->prefer('return=representation');
      $paypalRequest->body = [
        'intent' => 'CAPTURE',
        'purchase_units' => [
          [
            'amount' => [
              'currency_code' => $currencyCode,
              'value' => number_format($order->net_total, 2, '.', ''),
            ],
            'description' => 'Payment for order ' . $order->order_number,
          ]
        ],
      ];

      $client = PaypalClient::client();
      $response = $client->execute($paypalRequest);

      if ($response->statusCode !== 201) {
        return response()->json(['error' => 'Failed to create PayPal order'], 500);
      }

      return response()->json(['id' => $response->result->id]);
    } catch (HttpException $e) {
      return response()->json(['error' => 'PayPal API error: ' . $e->getMessage()], 500);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
    }
  }


  public function paypalCaptureOrder(Request $request): JsonResponse
  {
    try {
      $order = Order::where('order_number', $request->order_number)->firstOrFail();
      $paypalRequest = new OrdersCaptureRequest($request->order_id);
      $paypalRequest->prefer('return=representation');

      $client = PaypalClient::client();
      $response = $client->execute($paypalRequest);

      if ($response->statusCode !== 201 || $response->result->status !== 'COMPLETED') {
        $order->update([
          'payment_status' => 2,
          'payment_failure_reason' => 'PayPal capture failed: ' . json_encode($response->result),
        ]);
        return response()->json(['error' => 'Payment capture failed'], 400);
      }

      $this->updateOrderPayment($request->order_number, 'PayPal', $response->result);
      return response()->json([
        'success' => true,
        'message' => 'Payment successful.',
        'order_number' => $request->order_number,
      ]);
    } catch (HttpException $e) {
      return response()->json(['error' => 'PayPal API error: ' . $e->getMessage()], 500);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
    }
  }
}
