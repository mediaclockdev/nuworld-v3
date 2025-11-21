<?php

namespace App\Livewire\ProductManage;

use App\Livewire\BaseComponent;
use App\Models\BestSeller;

class BestSellerTable extends BaseComponent
{
  public string $filename = 'best_sellers';

  public $startDate = null;
  public $endDate = null;

  public array $columnAliases = [
    'sl'                    => 'Sl.',
    'product->name'         => 'Product Name',
    'variant->name'          => 'Product variant',
    'created_by'            => 'Created By',
    'created_at'            => 'Created Date',
    'updated_by'            => 'Updated By',
    'updated_at'            => 'Updated Date',
  ];

  public function __construct()
  {
    $this->sortColumn = 'product_id';
    $this->sortDirection = 'DESC';
  }

  /**
   * Get the query builder instance for the table.
   */
  public function getQuery(bool $filtered = false)
  {
    $query = BestSeller::with(['product', 'variant'])
      ->orderBy($this->sortColumn, $this->sortDirection);

    if ($filtered) {
      $query->search($this->search ?? '')
        ->when($this->startDate && $this->endDate, function ($query) {
          $query->where(function ($q) {
            $q->whereBetween('created_at', [$this->startDate, $this->endDate])
              ->orWhereBetween('updated_at', [$this->startDate, $this->endDate]);
          });
        });
    }

    return $query;
  }


  /**
   * Export data to CSV.
   */
  public function exportToCSV(bool $filtered = false)
  {
    return $this->exportCSVTrait(
      $this->getQuery($filtered),
      $this->columnAliases,
      $this->sortColumn,
      $this->sortDirection,
      $this->filename
    );
  }

  /**
   * Render the table component.
   */
  public function render()
  {
    $best_sellers = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($best_sellers->currentPage() - 1) * $best_sellers->perPage() + 1;

    return view('livewire.product-manage.best-seller-table', [
      'best_sellers' => $best_sellers,
      'serialNumber' => $serialNumber,
    ]);
  }
}
