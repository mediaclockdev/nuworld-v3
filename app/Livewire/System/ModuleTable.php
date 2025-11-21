<?php

namespace App\Livewire\System;

use App\Livewire\BaseComponent;
use App\Models\Module;

class ModuleTable extends BaseComponent // For Pagination and Export CSV
{
  public $filename = '';

  public $columnsWithAliases = [
    'sl'          => 'Sl.',
    'name'        => 'Module Name',
    'sequence'    => 'Sequence',
    'status'      => 'Status',
    'created_by'  => 'Created By',
    'created_at'  => 'Created Date',
    'updated_by'  => 'Updated By',
    'updated_at'  => 'Updated Date',
  ];

  public function mount()
  {
    $this->sortColumn = 'sequence';
    $this->sortDirection = 'ASC';
  }

  public function getQuery($filtered = false)
  {
    $query = Module::orderBy($this->sortColumn, $this->sortDirection);

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
    $Modules = $this->getQuery(true)->paginate($this->perPage);

    $serialNumber = ($Modules->currentPage() - 1) * $Modules->perPage() + 1;

    return view('livewire.system.module-table', ['modules' => $Modules, 'serialNumber' => $serialNumber]);
  }
}
