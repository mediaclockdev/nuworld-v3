<?php

namespace App\Livewire\Promotions;

use App\Livewire\BaseComponent;
use App\Models\Coupon;

class CouponTable extends BaseComponent
{
  public string $filename = 'coupons';

  public $startDate = null;
  public $endDate = null;

  public array $columnAliases = [
    'sl'                => 'Sl.',
    'code'              => 'Coupon Code',
    'type'              => 'Type',
    'discount_amount'   => 'Discount Amount',
    'max_discount'      => 'Max Discount',
    'min_order_value'   => 'Min Order Value',
    'max_uses'          => 'Max Uses',
    'per_user_limit'    => 'Per User Limit',
    'valid_from'        => 'Valid From',
    'valid_to'          => 'Valid To',
    'status'            => 'Status',
    'created_by'        => 'Created By',
    'created_at'        => 'Created Date',
    'updated_by'        => 'Updated By',
    'updated_at'        => 'Updated Date',
  ];

  public function __construct()
  {
    $this->sortColumn = 'created_at';
    $this->sortDirection = 'DESC';
  }
  /**
   * Get the query builder instance for the table.
   */
  public function getQuery(bool $filtered = false)
  {
    $query = Coupon::query()->orderBy($this->sortColumn, $this->sortDirection);

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
    $coupons = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($coupons->currentPage() - 1) * $coupons->perPage() + 1;

    return view('livewire.promotions.coupon-table', [
      'coupons' => $coupons,
      'serialNumber' => $serialNumber,
    ]);
  }
}
