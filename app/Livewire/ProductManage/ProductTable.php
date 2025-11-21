<?php

namespace App\Livewire\ProductManage;

use App\Livewire\BaseComponent;
use App\Models\Product;

class ProductTable extends BaseComponent
{
  public $filename = '';

  public $columnsWithAliases = [
    'sl'                  => 'Sl.',
    'category->title'     => 'Category',
    'name'                => 'Name',
    'sku'                 => 'SKU',
    'status'              => 'Status',
    'created_by'          => 'Created By',
    'created_at'          => 'Created Date',
    'updated_by'          => 'Updated By',
    'updated_at'          => 'Updated Date',
  ];

  public function __construct()
  {
    $this->sortColumn = 'name';
    $this->sortDirection = 'ASC';
  }

  public function getQuery($filtered = false)
  {
    $query = Product::with('category')
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
    $products = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($products->currentPage() - 1) * $products->perPage() + 1;

    return view('livewire.product-manage.product-table', ['products' => $products, 'serialNumber' => $serialNumber]);
  }
}
