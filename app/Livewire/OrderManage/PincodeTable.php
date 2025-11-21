<?php

namespace App\Livewire\OrderManage;

use App\Livewire\BaseComponent;
use App\Models\Pincode;

class PincodeTable extends BaseComponent
{

  public $filename = '';
  public $orderStatus = ['InActive', 'Active'];
  public $statusColor = ['bg-danger', 'bg-success'];


  public $columnsWithAliases = [
    'sl'                  => 'Sl.',
    'code'                => 'Code',
    'status'              => 'Status',
    'estimate_days'       => 'Estimate Days',
    'created_at'          => 'Added On',
  ];

  public function __construct()
  {
    $this->sortColumn = 'code';
    $this->sortDirection = 'ASC';
  }

  public function getQuery($filtered = false)
  {
    $query = Pincode::query();

    if ($filtered) {
      $query->search($this->search)
        ->when($this->startDate && $this->endDate, function ($query) {
          $query->where(function ($q) {
            $q->whereBetween('created_at', [$this->startDate, $this->endDate])
              ->orWhereBetween('updated_at', [$this->startDate, $this->endDate]);
          });
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
    $pincodes = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($pincodes->currentPage() - 1) * $pincodes->perPage() + 1;
    return view('livewire.order-manage.pincode-table', ['pincodes' => $pincodes, 'serialNumber' => $serialNumber]);
  }
}
