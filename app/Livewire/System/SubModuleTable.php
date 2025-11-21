<?php

namespace App\Livewire\System;

use App\Models\SubModule;
use App\Livewire\BaseComponent;
use App\Models\Module;
use Vinkla\Hashids\Facades\Hashids;

class SubModuleTable extends BaseComponent // For Pagination and Export CSV
{
  public $filename = '';
  public $moduleID;

  public $columnsWithAliases = [
    'sl'           => 'Sl.',
    'module->name' => 'Module',
    'name'         => 'Submodule',
    'sequence'     => 'Sequence',
    'status'       => 'Status',
    'created_by'   => 'Created By',
    'created_at'   => 'Created Date',
    'updated_by'   => 'Updated By',
    'updated_at'   => 'Updated Date',
  ];

  public function __construct()
  {
    $this->listeners = array_merge($this->listeners, [
      'moduleChangedComponent' => 'moduleChanged',
    ]);

    $this->sortColumn = 'module_id';
    $this->sortDirection = 'ASC';
  }

  public function moduleChanged($moduleId)
  {
    $this->moduleID = $moduleId ? (Hashids::decode($moduleId)[0] ?? '') : '';
  }

  public function getQuery($filtered = false)
  {
    $query = SubModule::orderBy($this->sortColumn, $this->sortDirection);

    if ($this->moduleID) {
      $query->where('module_id', $this->moduleID);
    }

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
    $modules = Module::where('status', 1)->get();

    $SubModules = $this->getQuery(true)->paginate($this->perPage);

    $serialNumber = ($SubModules->currentPage() - 1) * $SubModules->perPage() + 1;

    return view('livewire.system.sub-module-table', ['submodules' => $SubModules, 'serialNumber' => $serialNumber, 'modules' => $modules]);
  }
}
