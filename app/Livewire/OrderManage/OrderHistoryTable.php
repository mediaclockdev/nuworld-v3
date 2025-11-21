<?php

namespace App\Livewire\OrderManage;

use App\Livewire\BaseComponent;
use App\Models\Order;
use App\Models\OrderHistory;

class OrderHistoryTable extends BaseComponent
{
  public $order;
  public function mount(Order $order)
  {
    $this->order = $order;
    $this->sortColumn = 'updated_at';
    $this->sortDirection = 'DESC';
  }

  public function getQuery($filtered = false)
  {
    $query = OrderHistory::where('order_id', $this->order->id)->orderBy($this->sortColumn, $this->sortDirection);

    // if ($filtered) {
    //   $query->search($this->search)
    //     ->when($this->startDate && $this->endDate, function ($query) {
    //       $query->where(function ($q) {
    //         $q->whereBetween('created_at', [$this->startDate, $this->endDate])
    //           ->orWhereBetween('updated_at', [$this->startDate, $this->endDate]);
    //       });
    //     });
    // }

    return $query;
  }
  public function render()
  {
    $orderHistory = $this->getQuery(true)->paginate($this->perPage);

    $serialNumber = ($orderHistory->currentPage() - 1) * $orderHistory->perPage() + 1;

    return view('livewire.order-manage.order-history-table', ['orderHistory' => $orderHistory, 'serialNumber' => $serialNumber]);
  }
}
