<?php

namespace App\Livewire\System;

use App\Livewire\BaseComponent;
use App\Models\Permission;

class PermissionTable extends BaseComponent
{
  public $filename = '';
  public $usedSlugs = [];
  public $defaultSlugs = ['view', 'create', 'edit', 'delete', 'export', 'import'];
  public $checkBoxFlag = false;

  public $columnsWithAliases = [
    'sl'          => 'Sl.',
    'name'        => 'Name',
    'slug'        => 'Slug',
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
    $query = Permission::orderBy($this->sortColumn, $this->sortDirection);
    $slugs = $query->pluck('slug')->toArray();

    $this->checkBoxFlag = count($this->defaultSlugs) !== count($slugs);

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
    $Permissions = $this->getQuery(true)->paginate($this->perPage);

    $serialNumber = ($Permissions->currentPage() - 1) * $Permissions->perPage() + 1;

    return view('livewire.system.permission-table', ['permissions' => $Permissions, 'serialNumber' => $serialNumber, 'checkBoxFlag' => $this->checkBoxFlag]);
  }
}
