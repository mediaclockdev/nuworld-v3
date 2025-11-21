<?php

namespace App\Livewire;

use App\Traits\ExportCSV;
use Livewire\Component;
use Livewire\WithPagination;

class BaseComponent extends Component
{
  use WithPagination, ExportCSV;

  protected $listeners = ['refreshComponent' => 'refresh', 'exportCSVComponent' => 'exportToCSV', 'updateDateRangeComponent' => 'updateDateRange'];

  public $search = '';
  public $status = '';
  public $perPage = 10;
  public $startDate;
  public $endDate;
  public $sortColumn = 'created_at';
  public $sortDirection = 'DESC';

  public function updateDateRange($start = null, $end = null)
  {
    $this->startDate = $start;
    $this->endDate = $end;
  }

  public function sortByCol($field)
  {
    $this->sortDirection = $this->sortColumn === $field && $this->sortDirection === 'ASC' ? 'DESC' : 'ASC';
    $this->sortColumn = $field;
  }

  public function updatedSearch()
  {
    $this->resetPage();
  }

  public function updatedPerPage()
  {
    $this->resetPage();
  }

  public function refresh()
  {
    $this->search = '';
    $this->startDate = null;
    $this->endDate = null;
    // $this->status = null;
    $this->resetPage();
  }
}
