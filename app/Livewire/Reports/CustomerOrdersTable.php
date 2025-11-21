<?php

namespace App\Livewire\Reports;

use App\Models\Order;
use Livewire\Component;
use App\Enums\OrderStatus;
use App\Livewire\BaseComponent;


class CustomerOrdersTable extends BaseComponent
{
  public $user_id;
  public $filename = 'customer-orders';

  public $orderStatus = ['Awaiting Confirmation', 'Confirmed', 'Cancellation Initiated', 'Cancelled', 'Shipped', 'Delivered', 'Return Accepted', 'Refund Done'];
  public $statusColor = ['bg-danger', 'bg-success', 'bg-danger', 'bg-danger', 'bg-success', 'bg-success', 'bg-warning', 'bg-primary'];

  public $columnsWithAliases = [
    'sl'            => 'Sl.',
    'order_number'  => 'Order Number',
    'net_total'     => 'Net Total',
    'order_status'  => 'Order Status',
    'created_at'    => 'Order Date',
  ];

  public function __construct()
  {
    $this->sortColumn = 'created_at';
    $this->sortDirection = 'DESC';
  }

  public function mount($user_id)
  {
    $this->user_id = $user_id;
  }

  public function getQuery($filtered = false)
  {
    $query = Order::where('user_id', $this->user_id)
      // ->where('order_status', 5) // Only delivered orders
      ->select('id', 'order_number', 'net_total', 'order_status', 'created_at')
      ->orderBy($this->sortColumn, $this->sortDirection);

    if ($filtered) {
      $query->when($this->search, function ($q) {
        $q->where('order_number', 'like', '%' . str_replace(',', '', $this->search) . '%')
          ->orWhere('net_total', 'like', '%' . str_replace(',', '', $this->search) . '%');
      })->when($this->startDate && $this->endDate, function ($q) {
        $q->whereBetween('created_at', [$this->startDate, $this->endDate]);
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

    return view('livewire.reports.customer-orders-table', [
      'orders' => $orders,
      'serialNumber' => $serialNumber,
      'orderStatus' => OrderStatus::labels()
    ]);
  }
}
