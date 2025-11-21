<?php

namespace App\Livewire\System;

use App\Livewire\BaseComponent;
use App\Models\ScratchCardReward;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScratchCardRewardTable extends BaseComponent
{
  public $filename = 'scratch_card_rewards';
  public $orderStatus = ['InActive', 'Active'];
  public $statusColor = ['bg-danger', 'bg-success'];

  public $columnsWithAliases = [
    'sl' => 'Sl.',
    'type' => 'Type',
    'value' => 'Value',
    'code' => 'Coupon Code',
    'product' => 'Product',
    'validity_period' => 'Validity Period',
    'status' => 'Status',
    'valid_from' => 'From Date',
    'valid_to' => 'To Date',
    'created_at' => 'Created At',
  ];

  public function __construct()
  {
    $this->sortColumn = 'created_at';
    $this->sortDirection = 'DESC';
  }

  public function getQuery($filtered = false)
  {
    $query = ScratchCardReward::select(
      'scratch_card_rewards.id',
      'scratch_card_rewards.type',
      'scratch_card_rewards.value',
      // DB::raw("JSON_UNQUOTE(JSON_EXTRACT(scratch_card_rewards.conditions, '$.coupon_code')) as code"),
      'scratch_card_rewards.coupon_code as code',
      DB::raw("CASE
            WHEN JSON_EXTRACT(scratch_card_rewards.conditions, '$.product_ids') IS NOT NULL
                THEN (
                    SELECT GROUP_CONCAT(products.name SEPARATOR ', ')
                    FROM products
                    WHERE FIND_IN_SET(products.id, REPLACE(REPLACE(REPLACE(JSON_EXTRACT(scratch_card_rewards.conditions, '$.product_ids'), '[', ''), ']', ''), ' ', ''))
                )
            WHEN JSON_UNQUOTE(JSON_EXTRACT(scratch_card_rewards.conditions, '$.product')) = 'any'
              THEN 'Any Product'
          ELSE 'N/A'
        END as product"),
      'scratch_card_rewards.validity_period',
      'scratch_card_rewards.status',
      DB::raw("DATE_FORMAT(scratch_card_rewards.valid_from, '%Y-%m-%d') as valid_from"),
      DB::raw("DATE_FORMAT(scratch_card_rewards.valid_to, '%Y-%m-%d') as valid_to"),
      'scratch_card_rewards.created_at'
    );

    if ($filtered) {
      $query->search($this->search)
        ->when($this->startDate && $this->endDate, function ($query) {
          $query->where(function ($q) {
            $q->whereBetween('created_at', [$this->startDate, $this->endDate])
              ->orWhereBetween('updated_at', [$this->startDate, $this->endDate]);
          });
        });
    }
    return $query->orderBy('scratch_card_rewards.' . $this->sortColumn, $this->sortDirection);
  }

  public function exportToCSV($filtered = false)
  {
    return $this->exportCSVTrait($this->getQuery($filtered), $this->columnsWithAliases, $this->sortColumn, $this->sortDirection, $this->filename);
  }

  public function render()
  {
    $today = Carbon::now()->toDateString();
    ScratchCardReward::where('status', 1)
      ->whereDate('valid_to', '<', $today)
      ->update(['status' => 0]);

    $rewards = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($rewards->currentPage() - 1) * $rewards->perPage() + 1;
    return view('livewire.system.scratch-card-reward-table', ['rewards' => $rewards, 'serialNumber' => $serialNumber]);
  }
}
