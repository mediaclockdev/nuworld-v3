<?php

namespace App\Http\Controllers\Backend\OrderManage;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Requests\Backend\OrderManage\OrderHistoryRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Enums\OrderStatus;

class ShippingController extends Controller
{
  protected string $name;
  protected $model;

  public function __construct()
  {
    $this->name = 'Shipping Management';
    $this->model = Order::class;
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  public function index(): View
  {
    return view('backend.pages.order-manage.shipping.index', ['cardHeader' => $this->name . ' List']);
  }

  public function edit(int $id = 0): View
  {
    $order = $this->model::with('orderHistories')->find($id);

    $latestHistory = $order->orderHistories->sortByDesc('id')->first(); // or use 'created_at' if preferred

    $scheduledDate = $latestHistory && $latestHistory->scheduled_date
      ? \Carbon\Carbon::parse($latestHistory->scheduled_date)->format('d/m/Y')
      : now()->format('d/m/Y');

    $scheduledTime = $latestHistory && $latestHistory->scheduled_time
      ? $latestHistory->scheduled_time
      : now()->format('H:i');

    return view('backend.pages.order-manage.shipping.form', [
      'cardHeader' => 'Edit ' . $this->name,
      'order' => $order,
      'scheduledDate' => $scheduledDate,
      'scheduledTime' => $scheduledTime,
      'shippingStatus' => OrderStatus::labels(),
    ]);
  }

  public function update(OrderHistoryRequest $request, string $id = ''): JsonResponse
  {
    return $this->model::orderStatusUpdate($request, $id);
  }
}
