<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Frontend\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Http\Response;

class OrderController extends Controller
{
  protected $orderService;

  /**
   * Inject the OrderService dependency.
   */
  public function __construct(OrderService $orderService)
  {
    $this->orderService = $orderService;
  }

  public function index(): View
  {
    $data = [];
    $data['title'] = 'My Orders';
    $data = $this->orderService->getAllOrderData();
    //pd($data);
    return view('frontend.pages.user.orders.index', $data);
  }
  /**
   * Show the order confirmation page.
   */
  public function confirmation($order_number = null): View
  {
    $data = [];

    $data['title'] = 'Order Confirmation';

    $data += $this->orderService->getOrderConfirmationData($order_number);

    return view('frontend.pages.checkout.order_confirmation', $data);
  }

  public function orderDetails($order_number = null): View
  {
    $data = [];

    $data['title'] = 'Order Details';

    $data += $this->orderService->getOrderConfirmationData($order_number);

    return view('frontend.pages.user.orders.order-details', $data);
  }

  /**
   * Show the order tracking page based on order number.
   */
  public function tracking($order_number): View
  {
    $data['title'] = 'Order Tracking';
    $data += $this->orderService->getOrderTrackingData($order_number);

    return view('frontend.pages.user.orders.show', $data);
  }

  public function orderReturn(Request $request): JsonResponse
  {
    return $this->orderService->getOrderReturnData($request->value);
  }

  public function orderReturnSave(Request $request): JsonResponse
  {
    return $this->orderService->orderReturnSave($request);
  }

  public function downloadInvoice(string $orderId): Response
  {
    return $this->orderService->downloadInvoice($orderId);
  }

  public function getReward($order_number): JsonResponse
  {
    try {
      $reward = $this->orderService->getRewardForOrder($order_number);

      return response()->json([
        'success' => true,
        'reward' => $reward ? [
          'type' => $reward->scratchCardReward->type,
          'value' => $reward->scratchCardReward->value,
          'conditions' => $reward->scratchCardReward->conditions,
          'coupon_code' => $reward->scratchCardReward->coupon_code,
          'scratch_card_code' => $reward->scratch_card_code
        ] : null
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to fetch reward.'
      ], 500);
    }
  }
}
