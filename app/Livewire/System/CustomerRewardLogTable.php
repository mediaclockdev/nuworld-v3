<?php

namespace App\Livewire\System;

use App\Livewire\BaseComponent;
use App\Models\CustomerRewardLog;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;

class CustomerRewardLogTable extends BaseComponent
{
  public $filename = 'customer_reward_logs';
  public $userId = null;

  public $columnsWithAliases = [
    'sl'            => 'Sl.',
    'name'          => 'Customer Name',
    'email'         => 'Email',
    'order_number'  => 'Order Number',
    'created_at'    => 'Added On',
  ];

  public function mount($userId = null)
  {
    $this->sortColumn = 'created_at';
    $this->sortDirection = 'Asc';
    if ($userId) {
      $this->userId = Hashids::decode($userId)[0] ?? null;
    }
  }

  public function getQuery($filtered = false)
  {
    $query = Order::with(['user'])->where(['orders.coupon_type' => 2])->where('orders.coupon_id', '!=', null)
      ->select(
        'orders.*',
        'users.email',
        DB::raw("CONCAT_WS(' ', users.first_name, users.middle_name, users.last_name) as name"),
        // 'customer_rewards.scratch_card_code',
        // 'customer_rewards.status'
      )
      ->join('users', 'users.id', '=', 'orders.user_id');
    // ->join('customer_rewards', 'customer_rewards.scratch_card_reward_id', '=', 'orders.coupon_id');

    if ($this->userId) {
      $query->where('orders.user_id', $this->userId);
    }

    if ($filtered) {
      $query->search($this->search)
        ->when($this->startDate && $this->endDate, function ($query) {
          $query->where(function ($q) {
            $q->whereBetween('orders.created_at', [$this->startDate, $this->endDate])
              ->orWhereBetween('orders.updated_at', [$this->startDate, $this->endDate]);
          });
        });
    }

    $sortColumn = match ($this->sortColumn) {
      'email' => 'users.email',
      'name' => DB::raw("CONCAT_WS(' ', users.first_name, users.middle_name, users.last_name)"),
      'order_number' => 'orders.order_number',
      'scratch_card_code' => 'customer_rewards.scratch_card_code',
      'status' => 'customer_rewards.status',
      default => "orders.{$this->sortColumn}",
    };

    return $query->orderBy($sortColumn, $this->sortDirection);
  }
  public function exportToCSV($filtered = false)
  {
    return $this->exportCSVTrait($this->getQuery($filtered), $this->columnsWithAliases, $this->sortColumn, $this->sortDirection, $this->filename);
  }

  public function render()
  {
    $rewardLogs = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($rewardLogs->currentPage() - 1) * $rewardLogs->perPage() + 1;
    return view('livewire.system.customer-reward-log-table', ['customerRewardLogs' => $rewardLogs, 'serialNumber' => $serialNumber]);
  }
}
