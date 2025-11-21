<?php

namespace App\Livewire\OrderManage;

use App\Livewire\BaseComponent;
use App\Models\Order;

class ShippingTable extends BaseComponent
{
  public $filename = '';

  public $orderStatus = ['Awaiting Confirmation', 'Confirmed', 'Cancellation Initiated', 'Cancelled', 'Shipped', 'Delivered', 'Return Accepted', 'Refund Done'];
  public $statusColor = ['bg-danger', 'bg-success', 'bg-danger', 'bg-danger', 'bg-success', 'bg-success', 'bg-warning', 'bg-primary'];

  public $columnsWithAliases = [
    'sl'                  => 'Sl.',
    'order_number'        => 'Order Number',
    'status'              => 'Status',
    'created_by'          => 'Created By',
    'created_at'          => 'Created Date',
    'updated_by'          => 'Updated By',
    'updated_at'          => 'Updated Date',
  ];

  public function __construct()
  {
    $this->sortColumn = 'order_number';
    $this->sortDirection = 'DESC';
  }

  public function getQuery($filtered = false)
  {
    $query = Order::orderBy($this->sortColumn, $this->sortDirection);

    if ($filtered) {
      $query->search($this->search)
        ->when($this->startDate && $this->endDate, function ($query) {
          $query->where(function ($q) {
            $q->whereBetween('created_at', [$this->startDate, $this->endDate])
              ->orWhereBetween('updated_at', [$this->startDate, $this->endDate]);
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

    return view('livewire.order-manage.shipping-table', ['orders' => $orders, 'serialNumber' => $serialNumber]);
  }
}
