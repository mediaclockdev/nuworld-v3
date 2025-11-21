<?php

namespace App\Livewire\InventoryManage;

use App\Livewire\BaseComponent;
use App\Models\Inventory;

class InventoriesTable extends BaseComponent
{
  public $filename = '';
  public $page = 'inventory';

  public $columnsWithAliases = [
    'sl'                    => 'Sl.',
    'product->name'         => 'Product Name',
    'variant->sku'          => 'Product variant SKU',
    'quantity'              => 'Stock',
    'threshold'             => 'Threshold',
    'max_selling_quantity'  => 'Max Selling Quantity',
    'created_at'            => 'Created Date'
  ];

  /* public function __construct()
  {
    $this->sortColumn = 'product_id';
    $this->sortDirection = 'ASC';
  } */

  public function mount($page = 'inventory')
  {
    $this->page = $page;

    // Set default sorting based on the page
    if ($this->page === 'inventory-analytics') {
      $this->sortColumn = 'quantity';
      $this->sortDirection = 'DESC';
    } else {
      $this->sortColumn = 'product_id';
      $this->sortDirection = 'ASC';
    }
  }

  public function getQuery($filtered = false)
  {
    $query = Inventory::with(['product', 'variant'])
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
    $inventories = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($inventories->currentPage() - 1) * $inventories->perPage() + 1;

    return view('livewire.inventory-manage.inventories-table', ['inventories' => $inventories, 'serialNumber' => $serialNumber]);
  }
}
