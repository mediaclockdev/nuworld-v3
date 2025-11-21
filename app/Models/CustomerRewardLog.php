<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRewardLog extends Model
{
  protected $table = 'customer_reward_logs';

  protected $fillable = [
    'customer_id',
    'order_id',
    'customer_reward_id',
  ];

  public function customer()
  {
    return $this->belongsTo(User::class, 'customer_id');
  }

  public function order()
  {
    return $this->belongsTo(Order::class, 'order_id');
  }

  public function customerReward()
  {
    return $this->belongsTo(CustomerReward::class, 'customer_reward_id');
  }

  public static function scopeSearch($query, $search)
  {
    if (!$search) {
      return;
    }

    $query->where(function ($q) use ($search) {
      $q->orWhereHas('order', function ($orderQuery) use ($search) {
        $orderQuery->where('order_number', 'like', "%{$search}%");
      })
        ->orWhereHas('customer', function ($userQuery) use ($search) {
          $userQuery->where('users.email', 'like', "%{$search}%")
            ->orWhereRaw("CONCAT_WS(' ', users.first_name, users.middle_name, users.last_name) like ?", ["%{$search}%"]);
        });
    });
  }
}
