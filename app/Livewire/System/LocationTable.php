<?php

namespace App\Livewire\System;

use App\Livewire\BaseComponent;
use App\Models\Country;
use App\Models\State;
use Vinkla\Hashids\Facades\Hashids;

class LocationTable extends BaseComponent
{
  public $countryID = '';
  public $filename = '';

  public $columnsWithAliases = [
    'sl'              => 'Sl.',
    'country->name'   => 'Country',
    'country->code'   => 'Country Code',
    'name'            => 'State Name',
    'created_by'      => 'Created By',
    'created_at'      => 'Created Date',
    'updated_by'      => 'Updated By',
    'updated_at'      => 'Updated Date',
  ];

  public function __construct()
  {
    $this->listeners = array_merge($this->listeners, [
      'countryChangedComponent' => 'countryChanged',
    ]);

    $this->sortColumn = 'country_id';
    $this->sortDirection = 'ASC';
  }

  public function countryChanged($countryId)
  {
    $this->countryID = $countryId ? Hashids::decode($countryId)[0] : '';
  }

  public function getQuery($filtered = false)
  {
    $query = State::with('country')->orderBy($this->sortColumn, $this->sortDirection);
    if ($this->countryID) {
      $query->where('country_id', $this->countryID);
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
    $Countries = Country::all();

    $locations = $this->getQuery(true)->paginate($this->perPage);

    $serialNumber = ($locations->currentPage() - 1) * $locations->perPage() + 1;

    return view('livewire.system.location-table', ['locations' => $locations, 'serialNumber' => $serialNumber, 'countries' => $Countries]);
  }
}
