<?php

namespace App\Livewire\ProductManage;

use App\Livewire\BaseComponent;
use App\Models\Product;
use App\Models\ProductVariant;
use Vinkla\Hashids\Facades\Hashids;

class VariationsTable extends BaseComponent
{
  public $productID = 0;
  public $filename = '';
  public $dropdown;

  public $columnsWithAliases = [
    'sl'                  => 'Sl.',
    'name'                => 'Name',
    'sku'                 => 'SKU',
    'regular_price'       => 'Price',
    'status'              => 'Status',
    'created_by'          => 'Created By',
    'created_at'          => 'Created Date',
    'updated_by'          => 'Updated By',
    'updated_at'          => 'Updated Date',
  ];

  public function __construct(bool $dropdown = true)
  {
    $this->listeners = array_merge($this->listeners, [
      'updateValue' => 'setValues',
    ]);

    $this->sortColumn = 'name';
    $this->sortDirection = 'ASC';

    $this->dropdown = $dropdown;
  }

  public function setValues($id)
  {
    $this->productID = $id;
  }

  public function getQuery($filtered = false)
  {
    $query = ProductVariant::with('images.gallery')
      ->when($this->productID, function ($query) {
        $query->where('product_id', Hashids::decode($this->productID)[0]);
      })
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
    $basePath = asset('public/storage/uploads/media/products');

    $products = Product::where('status', 1)->get(['id', 'name']);

    $productVariants = $this->getQuery(true)->paginate($this->perPage);

    $serialNumber = ($productVariants->currentPage() - 1) * $productVariants->perPage() + 1;

    return view('livewire.product-manage.variations-table', ['productVariants' => $productVariants, 'basePath' => $basePath, 'serialNumber' => $serialNumber, 'products' => $products, 'dropdown' => $this->dropdown]);
  }
}
