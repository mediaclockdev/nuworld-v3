<?php

namespace App\Livewire\OrderManage;

use App\Livewire\BaseComponent;
use App\Models\User;

class CustomersTable extends BaseComponent
{
  public $filename = '';

  public $startDate;
  public $endDate;

  public $columnsWithAliases = [
    'sl'                                => 'Sl.',
    'full_name'                         => 'Name',
    'email'                             => 'Email',
    'phone'                             => 'Phone',
    'status_text'  => 'Status',
    'last_login'                            => 'Last Login',
    'created_at'                        => 'Joined Date',
  ];
  public function __construct()
  {
    $this->sortColumn = 'email';
    $this->sortDirection = 'DESC';
  }

  public function getQuery($filtered = false)
  {
    $query = User::select('users.*')
      ->selectSub(function ($q) {
        $q->from('user_activities')
          ->select('created_at')
          ->whereColumn('user_activities.user_id', 'users.id')
          ->orderByDesc('created_at')
          ->limit(1);
      }, 'last_login')
      ->whereIn('status', [1, 2]);

    if ($filtered) {
      $query->search($this->search)
        ->when($this->startDate && $this->endDate, function ($query) {
          $query->where(function ($q) {
            $q->whereBetween('created_at', [$this->startDate, $this->endDate])
              ->orWhereBetween('updated_at', [$this->startDate, $this->endDate]);
          });
        });
    }

    $query->when(in_array($this->sortColumn, ['first_name', 'middle_name', 'last_name', 'email', 'phone', 'created_at', 'updated_at', 'last_login']), function ($q) {
      $q->orderBy($this->sortColumn, $this->sortDirection);
    }, function ($q) {
      $q->orderBy('created_at', 'desc');
    });

    return $query;
  }


  public function exportToCSV($filtered = false)
  {
    return $this->exportCSVTrait($this->getQuery($filtered), $this->columnsWithAliases, $this->sortColumn, $this->sortDirection, $this->filename);
  }

  public function render()
  {
    $customers = $this->getQuery(true)->paginate($this->perPage);

    $serialNumber = ($customers->currentPage() - 1) * $customers->perPage() + 1;

    return view('livewire.order-manage.customers-table', ['customers' => $customers, 'serialNumber' => $serialNumber]);
  }
}
