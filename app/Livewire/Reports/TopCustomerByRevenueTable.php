<?php

namespace App\Livewire\Reports;

use App\Models\Order;
use App\Models\User;
use App\Livewire\BaseComponent;

class TopCustomerByRevenueTable extends BaseComponent
{

  public $filename = 'top-customers-by-revenue';

  public $columnsWithAliases = [
    'sl' => 'Sl.',
    'user_name' => 'Customer Name',
    'total_order_amount' => 'Total Order Amount',
    'order_count' => 'Total Order Count',
  ];
  public function __construct()
  {
    $this->sortColumn = 'total_order_amount';
    $this->sortDirection = 'DESC';
  }

  public function getQuery($filtered = false)
  {
    $query = Order::select('user_id')
      ->selectRaw('COUNT(*) as order_count')
      ->selectRaw('SUM(net_total) as total_order_amount')
      // ->where('order_status', 5) // Only delivered orders
      ->groupBy('user_id')
      ->join('users', 'orders.user_id', '=', 'users.id')
      ->selectRaw("TRIM(CONCAT(COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.last_name, ''))) as user_name")
      ->orderBy($this->sortColumn, $this->sortDirection);

    if ($filtered) {
      $query->when($this->search, function ($q) {
        $q->whereRaw("TRIM(CONCAT(COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.last_name, ''))) LIKE ?", '%' . $this->search . '%');
      })->when($this->startDate && $this->endDate, function ($q) {
        $q->whereBetween('orders.created_at', [$this->startDate, $this->endDate]);
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
    $topCustomers = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($topCustomers->currentPage() - 1) * $topCustomers->perPage() + 1;

    return view('livewire.reports.top-customer-by-revenue-table', [
      'customers' => $topCustomers,
      'serialNumber' => $serialNumber
    ]);
  }
}
