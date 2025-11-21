<?php

namespace App\Livewire\Promotions;

use App\Livewire\BaseComponent;
use App\Models\EmailSubscribe;

class NewsletterTable extends BaseComponent
{
  public $filename = '';

  public $columnsWithAliases = [
    'sl'              => 'Sl.',
    'email'           => 'Email',
    'created_at'      => 'Created Date'
  ];

    public function __construct()
  {
    $this->sortColumn = 'email';
    $this->sortDirection = 'ASC';
  }

  public function getQuery($filtered = false)
  {
    $query = EmailSubscribe::orderBy($this->sortColumn, $this->sortDirection);

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
    $newsletters = $this->getQuery(true)->paginate($this->perPage);

    $serialNumber = ($newsletters->currentPage() - 1) * $newsletters->perPage() + 1;

    return view('livewire.promotions.newsletter-table', ['newsletters' => $newsletters, 'serialNumber' => $serialNumber]);
  }
}
