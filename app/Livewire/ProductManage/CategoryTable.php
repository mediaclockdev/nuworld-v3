<?php

namespace App\Livewire\ProductManage;

use App\Livewire\BaseComponent;
use App\Models\ProductCategory;

class CategoryTable extends BaseComponent
{
  public $filename = '';

  public $columnsWithAliases = [
    'sl'                  => 'Sl.',
    'title'                => 'Name',
    'parent->title'       => 'Parent',
    'slug'                => 'Slug',
    'sequence'            => 'Sequence',
    'status'              => 'Status',
    'created_by'          => 'Created By',
    'created_at'          => 'Created Date',
    'updated_by'          => 'Updated By',
    'updated_at'          => 'Updated Date',
  ];

  public function __construct()
  {
    $this->sortColumn = 'parent_id';
    $this->sortDirection = 'ASC';
  }

  public function getQuery($filtered = false)
  {
    $query = ProductCategory::with('parent')
      ->orderBy($this->sortColumn, $this->sortDirection);

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
    $category = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($category->currentPage() - 1) * $category->perPage() + 1;

    return view('livewire.product-manage.category-table', ['categories' => $category, 'serialNumber' => $serialNumber]);
  }
}
