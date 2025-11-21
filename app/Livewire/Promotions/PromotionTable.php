<?php

namespace App\Livewire\Promotions;

use App\Livewire\BaseComponent;
use App\Models\Promotion;

class PromotionTable extends BaseComponent
{
  public string $filename = 'coupons';

  public $startDate = null;
  public $endDate = null;

  public array $columnAliases = [
    'sl'                    => 'Sl.',
    'promotion_mode_label'  => 'Promotion Mode',
    'name'                  => 'Promotion Name',
    'promotion_start_from'  => 'Promotion Start From',
    'promotion_end_to'      => 'Promotion End To',
    'status'                => 'Status',
    'created_by'            => 'Created By',
    'created_at'            => 'Created Date',
    'updated_by'            => 'Updated By',
    'updated_at'            => 'Updated Date',
  ];

  public function __construct()
  {
    $this->sortColumn = 'promotion_mode';
    $this->sortDirection = 'DESC';
  }

  /**
   * Get the query builder instance for the table.
   */
  public function getQuery(bool $filtered = false)
  {
    $query = Promotion::query()->orderBy($this->sortColumn, $this->sortDirection);

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
    $promotions = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($promotions->currentPage() - 1) * $promotions->perPage() + 1;

    return view('livewire.promotions.promotion-table', [
      'promotions' => $promotions,
      'serialNumber' => $serialNumber,
    ]);
  }
}
