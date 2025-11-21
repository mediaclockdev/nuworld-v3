<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\{CaptchaRequest, CheckoutCouponRequest, CheckoutRequest, PaymentRequest};
use App\Models\Order;
use App\Services\Backend\System\ScratchCardRewardService;
use App\Http\Resources\Api\Frontend\CouponResource;
use App\Models\Coupon;
use App\Models\CustomerReward;
use App\Models\CustomerRewardLog;
use App\Services\Frontend\{CartService, CheckoutService};
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\View\View;


class CheckoutController extends Controller
{
  public function __construct(protected CheckoutService $checkoutService,  protected CartService $cartService, protected ScratchCardRewardService $rewardService) {}

  public function index(): View
  {
    return view('frontend.pages.checkout.index', $this->checkoutService->getCheckoutData());
  }

  public function couponApply(CheckoutCouponRequest $request): JsonResponse
  {
    return $this->checkoutService->applyCoupon($request->validated());
  }

  public function couponRemove(): JsonResponse
  {
    return $this->checkoutService->removeCoupon();
  }

  public function process(CheckoutRequest $request): JsonResponse
  {
    return $this->checkoutService->processCheckout($request);
  }

  public function changeDefaultAddress(Request $request): JsonResponse
  {
    return $this->checkoutService->changeDefaultAddress($request->id, $request->type);
  }

  public function nextProcess(string $type, string $order_number): View
  {
    if ($type === 'confirm')
      return view('frontend.pages.checkout.confirm', ['order_number' => $order_number]);
    else {
      if ($type === 'stripe_payment') {
        $paymentDetails = $this->checkoutService->stripeInitiatePayment($order_number);
        return view('frontend.pages.checkout.payment', ['paymentDetails' => $paymentDetails]);
      } elseif ($type === 'paypal_payment') {
        $paymentDetails = $this->checkoutService->paypalInitiatePayment($order_number);
        return view('frontend.pages.checkout.paypal-payment', ['paymentDetails' => $paymentDetails]);
      }
    }
  }

  public function updateCODOrder(CaptchaRequest $request)
  {
    // return $this->checkoutService->updateOrderPayment($request->order_number, 'COD', null);
    $response = $this->checkoutService->updateOrderPayment($request->order_number, 'COD', null);
    // Assign a reward after order confirmation
    $order = Order::where('order_number', $request->order_number)->firstOrFail();
    $reward = $this->rewardService->assignReward($order);

    if (session('coupon_id')) {
      session()->forget(['coupon_id', 'coupon.code', 'coupon.discount']);
    }
    if (session('scratch_card_id')) { // Coupon apply
      CustomerReward::where('customer_id', auth()->id())->where('id', session('customer_reward_id'))->update(['status' => 2]);
      session()->forget(['scratch_card_id', 'reward.scratch_card_code', 'reward.scratch_card_discount', 'customer_reward_id']);
    }
    return $response;
  }

  public function processPayment(PaymentRequest $request)
  {
    $response = $this->checkoutService->stripeProcessPayment($request);
    if ($response->original['success'] === true)
      return redirect()->route('order.confirmation', ['order_number' => $request->order_number]);

    return redirect()->view('frontend.pages.checkout.payment-failed', ['order_number' => $request->order_number, 'error' => $response->original['message']]);
  }

  public function couponList()
  {
    $coupon = Coupon::where('is_active', true)->where('valid_from', '<=', now())->where('valid_to', '>=', now())->get();
    return CouponResource::collection($coupon);
  }

  public function paypalCreate(Request $request)
  {
    return $this->checkoutService->paypalCreateOrder($request);
  }

  public function paypalCapture(Request $request): JsonResponse
  {
    try {
      $response = $this->checkoutService->paypalCaptureOrder($request);

      // Check if the payment was successful
      if ($response->original['success'] ?? false) {
        $orderNumber = $request->order_number;
        $redirectUrl = route('order.confirmation', ['order_number' => $orderNumber]);
        return response()->json([
          'success' => true,
          'message' => 'Payment successful.',
          'order_number' => $orderNumber,
          'redirect' => $redirectUrl // Include redirect URL
        ]);
      }
      // Handle failure case
      return $response;
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }
}
