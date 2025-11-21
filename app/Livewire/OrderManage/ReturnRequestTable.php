<?php

namespace App\Livewire\OrderManage;

use App\Livewire\BaseComponent;
use App\Models\OrderReturn;

class ReturnRequestTable extends BaseComponent
{
  public $filename = '';

  public $columnsWithAliases = [
    'sl'                      => 'Sl.',
    'type'                    => 'Request Type',
    'order->order_number'     => 'Order Number',
    'user->email'             => 'Customer Email',
    'status'                  => 'Status',
    'requested_at'            => 'Requested Date',
    'reviewed_by'             => 'Reviewed By',
    'updated_at'              => 'Updated Date',
  ];
  public function __construct()
  {
    $this->sortColumn = 'requested_at';
    $this->sortDirection = 'DESC';
  }

  public function getQuery($filtered = false)
  {
    $query = OrderReturn::with('order', 'user')->orderBy($this->sortColumn, $this->sortDirection);

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
    $orderReturn = $this->getQuery(true)->paginate($this->perPage);

    $serialNumber = ($orderReturn->currentPage() - 1) * $orderReturn->perPage() + 1;

    return view('livewire.order-manage.return-request-table', ['orderReturn' => $orderReturn, 'serialNumber' => $serialNumber]);
  }
}
