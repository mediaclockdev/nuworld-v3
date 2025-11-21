<?php

namespace App\Livewire\System;

use App\Livewire\BaseComponent;
use App\Models\Department;

class DepartmentTable extends BaseComponent
{
  public $filename = '';

  public $columnsWithAliases = [
    'sl'          => 'Sl.',
    'name'        => 'Name',
    'status'      => 'Status',
    'created_by'  => 'Created By',
    'created_at'  => 'Created Date',
    'updated_by'  => 'Updated By',
    'updated_at'  => 'Updated Date',
  ];

  public function __construct()
  {
    $this->sortColumn = 'id';
    $this->sortDirection = 'ASC';
  }

  public function getQuery($filtered = false)
  {
    $query = Department::orderBy($this->sortColumn, $this->sortDirection);

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
    $Departments = $this->getQuery(true)->paginate($this->perPage);

    $serialNumber = ($Departments->currentPage() - 1) * $Departments->perPage() + 1;

    return view('livewire.system.department-table', ['departments' => $Departments, 'serialNumber' => $serialNumber]);
  }
}
