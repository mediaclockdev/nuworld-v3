<?php

namespace App\Livewire\System;

use App\Livewire\BaseComponent;
use App\Models\CustomerReward;
// use App\Models\ScratchCardReward;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class CustomerRewardTable extends BaseComponent
{
  public $filename = 'customer_rewards';

  public $columnsWithAliases = [
    'sl'                => 'Sl.',
    'name'              => 'Name',
    'email'             => 'Email',
    'scratch_card_code' => 'Scratch Card Code',
    'order_number'      => 'Order Number',
    'status_text'       => 'Status',
    'expiry_date'       => 'Expiry Date',
    'created_at'        => 'Added On',
  ];

  public function __construct()
  {
    $this->sortColumn = 'created_at';
    $this->sortDirection = 'Asc';
  }

  public function getQuery1($filtered = false)
  {
    $query = CustomerReward::with('customer')
      ->select('customer_rewards.*')
      ->join('users', 'users.id', '=', 'customer_rewards.customer_id');

    if ($filtered) {
      $query->search($this->search)
        ->when($this->startDate && $this->endDate, function ($query) {
          $query->where(function ($q) {
            $q->whereBetween('customer_rewards.created_at', [$this->startDate, $this->endDate])
              ->orWhereBetween('customer_rewards.updated_at', [$this->startDate, $this->endDate]);
          });
        });
    }

    $sortColumn = match ($this->sortColumn) {
      'email' => 'users.email',
      'name' => DB::raw("CONCAT_WS(' ', users.first_name, users.middle_name, users.last_name)"),
      default => "customer_rewards.{$this->sortColumn}",
    };
    return $query->orderBy($sortColumn, $this->sortDirection);
  }
  public function getQuery($filtered = false)
  {
    $query = CustomerReward::select(
      'customer_rewards.id',
      'customer_rewards.scratch_card_code',
      'customer_rewards.status',
      DB::raw("CASE
                WHEN customer_rewards.status = 1 THEN 'Active'
                WHEN customer_rewards.status = 2 THEN 'Used'
                WHEN customer_rewards.status = 0 OR customer_rewards.status = 3 THEN 'Expired'
                ELSE 'N/A'
            END as status_text"),
      'customer_rewards.expiry_date',
      'customer_rewards.created_at',
      'customer_rewards.updated_by',
      'customer_rewards.updated_at',
      DB::raw("CONCAT_WS(' ', users.first_name, users.middle_name, users.last_name) as name"),
      'users.email',
      'orders.order_number',
      'orders.id as order_id',
      'customer_rewards.customer_id as user_id'
    )
      ->join('users', 'users.id', '=', 'customer_rewards.customer_id')
      ->leftJoin('orders', 'orders.id', '=', 'customer_rewards.order_id');

    if ($filtered) {
      $query->search($this->search)
        ->when($this->startDate && $this->endDate, function ($query) {
          $query->where(function ($q) {
            $q->whereBetween('customer_rewards.created_at', [$this->startDate, $this->endDate])
              ->orWhereBetween('customer_rewards.updated_at', [$this->startDate, $this->endDate]);
          });
        });
    }

    $sortColumn = match ($this->sortColumn) {
      'email' => 'users.email',
      'name' => DB::raw("CONCAT_WS(' ', users.first_name, users.middle_name, users.last_name)"),
      'order_number' => 'orders.order_number',
      'status' => 'customer_rewards.status',
      default => "customer_rewards.{$this->sortColumn}",
    };
    return $query->orderBy($sortColumn, $this->sortDirection);
  }
  public function exportToCSV($filtered = false)
  {
    return $this->exportCSVTrait($this->getQuery($filtered), $this->columnsWithAliases, $this->sortColumn, $this->sortDirection, $this->filename);
  }

  public function render()
  {
    $rewards = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($rewards->currentPage() - 1) * $rewards->perPage() + 1;
    return view('livewire.system.customer-reward-table', ['customerRewards' => $rewards, 'serialNumber' => $serialNumber]);
  }
}
