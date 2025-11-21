<?php

namespace App\Services\Frontend;

use App\Models\CustomerReward;
use App\Models\Order;
use App\Models\OrderReturn;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;

use function Pest\Laravel\json;

class OrderService
{
  public function getOrderConfirmationData($order_number): array
  {
    $user = Auth::user();

    $order = Order::with(['orderProducts.variant', 'orderProducts.variant.product', 'orderHistories'])
      ->where('user_id', $user->id)
      ->where('order_number', $order_number)
      ->firstOrFail();

    return [
      'order' => $order,
      'items' => $order->orderProducts,
    ];
  }


  public function getOrderDetails($order_number): object
  {
    $user = Auth::user();

    $order = Order::with(['orderProducts.variant', 'orderProducts.variant.product', 'orderHistories'])
      ->where('user_id', $user->id)
      ->where('order_number', $order_number)
      ->firstOrFail();

    return $order;
  }

  public function getOrderTrackingData($order_number): array
  {
    $user = Auth::user();

    $order = Order::with(['orderProducts.variant', 'orderProducts.variant.product'])
      ->where('user_id', $user->id)
      ->where('order_number', $order_number)
      ->firstOrFail();

    return [
      'order' => $order,
      'items' => $order->orderProducts,
    ];
  }

  public function getAllOrderData(): array
  {
    $user = Auth::user();

    $orders = Order::with(['orderProducts.variant', 'orderProducts.variant.product', 'orderHistories'])
      ->where('user_id', $user->id)
      ->where('order_status', '!=', 0)
      ->orderBy('created_at', 'desc')
      ->get();
    return [
      'orders' => $orders,
      'items' => $orders->flatMap->orderProducts,
    ];
  }

  public function getOrderReturnData(string $orderId): JsonResponse
  {
    $id = Hashids::decode($orderId)[0] ?? null;

    if (!$id)
      return response()->json(['success' => false, 'message' => 'Invalid order id']);

    $orderReturn = OrderReturn::where('order_id', $id)->first();

    if ($orderReturn)
      return response()->json([
        'success' => true,
        'type' => ucfirst($orderReturn->type),
        'user_reason' => $orderReturn->reason,
        'current_status' => ucfirst($orderReturn->status),
        'response' => $orderReturn->admin_response ?? 'Under Review',
      ]);

    return response()->json(['success' => true, 'current_status' => null]);
  }

  public function orderReturnSave(Request $request): JsonResponse
  {
    $extract = explode('_', $request->order_type);
    $orderId = Hashids::decode($extract[0])[0];
    $orderType = $extract[1];
    $orderReturn = OrderReturn::updateOrCreate(
      ['order_id' => $orderId],
      ['order_id' => $orderId, 'user_id' => user()->id, 'type' => $orderType, 'reason' => $request->help_text, 'requested_at' => now()]
    );

    return response()->json(['success' => true, 'message' => __('response.success.submit', ['item' => 'Order Request'])]);
  }

  public function downloadInvoice(string $orderId): Response
  {
    $decodedOrderId = Hashids::decode($orderId)[0] ?? null;
    if (!$decodedOrderId) {
      abort(400, 'Invalid order ID.');
    }

    $order = Order::with([
      'orderProducts.variant.images' => function ($query) {
        $query->where('is_default', 1);
      },
      'coupon'
    ])->findOrFail($decodedOrderId);

    if (Auth::id() !== $order->user_id) {
      abort(403, 'Unauthorized action.');
    }

    $pdf = Pdf::loadView('backend.pages.order-manage.orders.invoice-order', compact('order'));

    // Apply custom PDF settings
    $pdf->setOption('page-width', '210mm');
    $pdf->setOption('page-height', '297mm');
    $pdf->setOption('disable-javascript', true);
    $pdf->setOption('margin-right', 0);
    $pdf->setOption('margin-left', 0);
    $pdf->setOption('margin-top', 0);
    $pdf->setOption('margin-bottom', 0);
    $pdf->setOption('isHtml5ParserEnabled', true);
    $pdf->setOption('isRemoteEnabled', true);

    return $pdf->download('Invoice-' . $order->order_number . '.pdf');
  }

  public function getRewardForOrder($order_number)
  {
    $user = Auth::user();

    $order = Order::where('user_id', $user->id)
      ->where('order_number', $order_number)
      ->firstOrFail();

    $reward = CustomerReward::with('scratchCardReward')
      ->where('customer_id', $user->id)
      ->where('created_at', '>=', $order->created_at)
      ->where('created_at', '<=', $order->created_at->addMinutes(20))
      ->latest()
      ->first();


    return $reward;
  }
}
