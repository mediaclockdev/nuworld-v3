<?php

namespace App\Livewire\Reports;

use App\Livewire\BaseComponent;
use App\Models\SearchQuery;

class SearchQueryTable extends BaseComponent
{
  public $filename = '';

  public $columnsWithAliases = [
    'sl' => 'Sl.',
    'query' => 'Search Query',
    'count' => 'Search Count',
  ];

  public function __construct()
  {
    $this->sortColumn = 'count';
    $this->sortDirection = 'DESC';
  }

  public function getQuery($filtered = false)
  {
    $query = SearchQuery::select('query', 'count', 'created_at')
      ->orderBy($this->sortColumn, $this->sortDirection ?? 'desc')
      ->orderBy('created_at', 'desc');

    if ($filtered) {
      $query->when($this->search, function ($q) {
        $q->where('query', 'like', '%' . $this->search . '%')
          ->orWhere('count', 'like', '%' . $this->search . '%');
      })->when($this->startDate && $this->endDate, function ($q) {
        $q->whereBetween('created_at', [$this->startDate, $this->endDate]);
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
    $searchQueries = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($searchQueries->currentPage() - 1) * $searchQueries->perPage() + 1;

    return view('livewire.reports.search-query-table', [
      'searchQueries' => $searchQueries,
      'serialNumber' => $serialNumber
    ]);
  }
}
