<?php

namespace App\Http\Controllers\Api\Frontend\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponse;
use App\Http\Requests\Frontend\CaptchaRequest;
use App\Http\Requests\Frontend\CheckoutCouponRequest;
use App\Http\Requests\Frontend\CheckoutRequest;
use App\Http\Resources\Api\Frontend\UserProfileResource;
use App\Services\Frontend\UserProfile;
use App\Http\Requests\Frontend\UpdateProfileRequest;
use App\Http\Resources\Api\Frontend\OrderResource;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\PaymentSettings;
use App\Services\Frontend\CheckoutService;
use App\Services\Frontend\OrderService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\{JsonResponse, Request};
use Vinkla\Hashids\Facades\Hashids;

class OrderController extends Controller
{
  public function __construct(private OrderService $orderService, private CheckoutService $checkoutService) {}
  // fetch Orders
  public function myOrders()
  {
    $orders = $this->orderService->getAllOrderData();
    $orderList = collect($orders['orders']);
    //pd($orderList->orderProducts);
    return ApiResponse::success(OrderResource::collection($orderList), __('response.success.fetch', ['item' => 'My Orders']));
  }

  public function orderDetails($order_number = null)
  {
    $order = $this->orderService->getOrderDetails($order_number);
    //pd($orders);


    return ApiResponse::success(OrderResource::make($order), __('response.success.fetch', ['item' => 'Order Details']));
  }

  public function downloadInvoice($orderNumber)
  {
    // Find order by order_number instead of ID
    $order = Order::with([
      'orderProducts.variant.images' => function ($query) {
        $query->where('is_default', 1);
      },
      'coupon'
    ])->where('order_number', $orderNumber)->firstOrFail();

    // Auth check
    if (auth()->user()->id !== $order->user_id) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Load PDF
    $pdf = Pdf::loadView('backend.pages.order-manage.orders.invoice-order', compact('order'));

    // PDF options
    $pdf->setOption('page-width', '210mm');
    $pdf->setOption('page-height', '297mm');
    $pdf->setOption('disable-javascript', true);
    $pdf->setOption('margin-right', 0);
    $pdf->setOption('margin-left', 0);
    $pdf->setOption('margin-top', 0);
    $pdf->setOption('margin-bottom', 0);
    $pdf->setOption('isHtml5ParserEnabled', true);
    $pdf->setOption('isRemoteEnabled', true);

    // Output content
    $pdfContent = $pdf->output();

    return response()->streamDownload(function () use ($pdfContent) {
      echo $pdfContent;
    }, 'Invoice-' . $order->order_number . '.pdf', [
      'Content-Type' => 'application/pdf',
    ]);
  }

  public function changePaymentMethod()
  {
    $paymentMethods = PaymentSettings::whereIn('gateway_name', ['Stripe', 'Cash On Delivery'])
      ->whereIn('gateway_mode', ['test', 'cod'])
      ->get()
      ->map(function ($method) {
        $id = $method->gateway_name === 'Cash On Delivery' ? 0 : 1;
        $method->payment_method = $id;

        // Assign logo based on gateway_name
        if ($method->gateway_name === 'Cash On Delivery') {
          $method->logo = asset('public/common/images/gateway_logos/cod.png');
        } elseif ($method->gateway_name === 'Stripe') {
          $method->logo = asset('public/common/images/gateway_logos/stripe.png');
        }

        return $method;
      });

    return ApiResponse::success(
      $paymentMethods,
      __('response.success.fetch', ['item' => 'Payment Methods'])
    );
  }



  public function couponList()
  {
    $couponList = Coupon::where('is_active', true)->whereDate('valid_from', '<=', now())->whereDate('valid_to', '>=', now())->get();
    return ApiResponse::success($couponList, __('response.success.fetch', ['item' => 'Coupon List']));
  }

  public function couponApply(CheckoutCouponRequest $request): JsonResponse
  {
    return $this->checkoutService->applyCouponApi($request->validated());
  }

  public function couponRemove(): JsonResponse
  {
    return $this->checkoutService->removeCouponApi();
  }

  public function process(CheckoutRequest $request): JsonResponse
  {
    return $this->checkoutService->processApiCheckout($request);
  }

  public function updateCODOrder(CaptchaRequest $request)
  {
    //dd($request->all());
    // pd($request->order_number);
    return $this->checkoutService->updateOrderPayment($request->order_number, 'COD', null);
  }
}
