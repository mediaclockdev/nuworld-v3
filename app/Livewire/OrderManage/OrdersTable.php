<?php

namespace App\Livewire\OrderManage;

use App\Enums\OrderStatus;
use App\Livewire\BaseComponent;
use App\Models\Order;
use Vinkla\Hashids\Facades\Hashids;

class OrdersTable extends BaseComponent
{
  public $filename = '';

  // public $paymentMethods = ['COD', 'Online'];
  public $orderStatus = ['Awaiting Confirmation', 'Confirmed', 'Cancellation Initiated', 'Cancelled', 'Shipped', 'Delivered', 'Return Accepted', 'Refund Done'];
  public $statusColor = ['bg-danger', 'bg-success', 'bg-danger', 'bg-danger', 'bg-success', 'bg-success', 'bg-warning', 'bg-primary'];

  public $columnsWithAliases = [
    'sl'                      => 'Sl.',
    'created_at'                    => 'Purchase Date',
    'order_number'            => 'Order Number',
    'order_status_text'       => 'Status',
    'net_total'               => 'Amount',
    'user->name'              => 'Customer',
    'user->email'             => 'Email',
    'payment_method_display'  => 'Payment Method',

  ];

  public $status = null;

  public function __construct()
  {
    $this->listeners = array_merge($this->listeners, [
      'updateValue' => 'setValues',
    ]);
    $this->sortColumn = 'created_at';
    $this->sortDirection = 'DESC';
  }

  public function setValues($status)
  {
    $this->status = Hashids::decode($status)[0] ?? null;
  }

  public function getQuery($filtered = false)
  {
    $query = Order::with(['user', 'orderProducts.variant']);
    if ($filtered) {
      $query->search($this->search)
        ->when($this->startDate && $this->endDate, function ($query) {
          $query->where(function ($q) {
            $q->whereBetween('created_at', [$this->startDate, $this->endDate])
              ->orWhereBetween('updated_at', [$this->startDate, $this->endDate]);
          });
        })
        ->when($this->status, function ($query) {
          $query->where('order_status', $this->status);
        });
    }
    return $query->orderBy($this->sortColumn, $this->sortDirection);
  }

  public function exportToCSV($filtered = false)
  {
    return $this->exportCSVTrait($this->getQuery($filtered), $this->columnsWithAliases, $this->sortColumn, $this->sortDirection, $this->filename);
  }

  public function render()
  {
    $orders = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($orders->currentPage() - 1) * $orders->perPage() + 1;

    return view('livewire.order-manage.orders-table', ['orders' => $orders, 'serialNumber' => $serialNumber, 'orderStatus' => OrderStatus::labels()]);
  }
}
