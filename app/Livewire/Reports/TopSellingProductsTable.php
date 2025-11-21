<?php

namespace App\Livewire\Reports;

use App\Livewire\BaseComponent;
use App\Models\OrderProduct;

class TopSellingProductsTable extends BaseComponent
{
  public $filename = '';

  public $columnsWithAliases = [
    'sl' => 'Sl.',
    'variant->category->title' => 'Category',
    'variant->name' => 'Variant Name',
    'variant->sku' => 'SKU',
    'order_count' => 'Order Count',
    'total_sales' => 'Total Sales',
  ];

  public function __construct()
  {
    $this->sortColumn = 'order_count';
    $this->sortDirection = 'DESC';
  }

  public function getQuery($filtered = false)
  {
    $query = OrderProduct::with('variant.product')
      ->select('variant_id')
      ->selectRaw('COUNT(*) as order_count')
      ->selectRaw('SUM(sell_price) as total_sales')
      ->groupBy('variant_id')
      ->orderBy($this->sortColumn ?? 'order_count', $this->sortDirection ?? 'desc');

    if ($filtered) {
      $query->search($this->search)
        ->when($this->startDate && $this->endDate, function ($q) {
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
    $topSellingProducts = $this->getQuery(true)->paginate($this->perPage);

    $serialNumber = ($topSellingProducts->currentPage() - 1) * $topSellingProducts->perPage() + 1;

    return view('livewire.reports.top-selling-products-table', ['products' => $topSellingProducts, 'serialNumber' => $serialNumber]);
  }
}
