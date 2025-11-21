<?php

namespace App\Livewire\OrderManage;

use App\Livewire\BaseComponent;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class ShippingBillingTable extends BaseComponent
{
  public $filename = '';

  public $orderStatus = ['Awaiting Confirmation', 'Confirmed', 'Cancellation Initiated', 'Cancelled', 'Shipped', 'Delivered', 'Return Accepted', 'Refund Done'];
  public $statusColor = ['bg-danger', 'bg-success', 'bg-danger', 'bg-danger', 'bg-success', 'bg-success', 'bg-warning', 'bg-primary'];

  public $columnsWithAliases = [
    'sl'                  => 'Sl.',
    'customer_name'       => 'Customer Name',
    'order_number'        => 'Order Number',
    'order_status_text'   => 'Status',
    'created_at'          => 'Delivery Date',
    'shipping_address'    => 'Shipping Address',
  ];

  public function __construct()
  {
    $this->sortColumn = 'order_number';
    $this->sortDirection = 'DESC';
  }

  public function getQuery($filtered = false)
  {
    $query = Order::select(
      'orders.*',
      DB::raw("TRIM(CONCAT(COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.last_name, ''))) as customer_name")
    )
      ->leftJoin('users', 'orders.user_id', '=', 'users.id')
      ->orderBy($this->sortColumn, $this->sortDirection);

    if ($filtered) {
      $query->search($this->search)
        ->when($this->startDate && $this->endDate, function ($query) {
          $query->where(function ($q) {
            $q->whereBetween('orders.created_at', [$this->startDate, $this->endDate])
              ->orWhereBetween('orders.updated_at', [$this->startDate, $this->endDate]);
          });
        });
    }
    return $query;
  }

  public function exportToCSV($filtered = false)
  {
    return $this->exportCSVTrait($this->getQuery($filtered), $this->columnsWithAliases, $this->sortColumn, $this->sortDirection, $this->filename);
  }

  public function render()
  {
    $orders = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($orders->currentPage() - 1) * $orders->perPage() + 1;

    return view('livewire.order-manage.shipping-billing-table', ['orders' => $orders, 'serialNumber' => $serialNumber]);
  }
}
