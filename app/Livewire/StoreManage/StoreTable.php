<?php

namespace App\Livewire\StoreManage;

use App\Livewire\BaseComponent;
use App\Models\Store;

class StoreTable extends BaseComponent
{

  public $filename = '';
  public $orderStatus = ['InActive', 'Active'];
  public $statusColor = ['bg-danger', 'bg-success'];

  public function __construct()
  {
    $this->sortColumn    = 'name';
    $this->sortDirection = 'Desc';
  }

  public $columnsWithAliases = [
    'sl'                => 'Sl.',
    'name'              => 'Name',
    'city'              => 'City',
    'country'           => 'Country',
    'status'            => 'Status',
    'address'           => 'Address',
    'created_by'        => 'Created By',
    'created_at'        => 'Created Date',
    'updated_by'        => 'Updated By',
    'updated_at'        => 'Updated Date',
  ];


  public function getQuery($filtered = false)
  {
      $query = Store::with(['country']);
      if ($filtered) {
          $query->when($this->search, function ($q) {
              $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('city', 'like', '%' . $this->search . '%')
                ->orWhere('address', 'like', '%' . $this->search . '%');
          })->when($this->startDate && $this->endDate, function ($q) {
              $q->whereBetween('created_at', [$this->startDate, $this->endDate]);
          });
      }
      if ($this->sortColumn === 'country.name') {
        $query->join('countries', 'stores.country_id', '=', 'countries.id')
            ->select('stores.*')
            ->orderBy('countries.name', $this->sortDirection);
      } else {
        $query->orderBy($this->sortColumn, $this->sortDirection);
      }
    return $query;
  }


  public function exportToCSV($filtered = false)
  {
    return $this->exportCSVTrait($this->getQuery($filtered), $this->columnsWithAliases, $this->sortColumn, $this->sortDirection, $this->filename);
  }

  public function render()
  {
    $stores = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($stores->currentPage() - 1) * $stores->perPage() + 1;
    return view('livewire.store-manage.store-table', ['stores' => $stores, 'serialNumber' => $serialNumber]);
  }
}
