<?php

namespace App\Livewire\ContentManage;

use App\Livewire\BaseComponent;
use App\Models\CmsPage;

class CmsPageTable extends BaseComponent
{
  public $filename = '';

  public $columnsWithAliases = [
    'sl'              => 'Sl.',
    'title'           => 'Title',
    'slug'            => 'Slug',
    'status'          => 'Status',
    'created_by'      => 'Created By',
    'created_at'      => 'Created Date',
    'updated_by'      => 'Updated By',
    'updated_at'      => 'Updated Date',
  ];

  public function __construct()
  {
    $this->sortColumn = 'title';
    $this->sortDirection = 'ASC';
  }

  public function getQuery($filtered = false)
  {
    $query = CmsPage::orderBy($this->sortColumn, $this->sortDirection);

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
    $CMSPages = $this->getQuery(true)->paginate($this->perPage);

    $serialNumber = ($CMSPages->currentPage() - 1) * $CMSPages->perPage() + 1;

    return view('livewire.content-manage.cms-page-table', ['cmsPages' => $CMSPages, 'serialNumber' => $serialNumber]);
  }
}
