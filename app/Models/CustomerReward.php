<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class CustomerReward extends Model
{
  protected $fillable = [
    'customer_id',
    'scratch_card_reward_id',
    'order_id',
    'scratch_card_code',
    'status',
    'expiry_date',
  ];

  protected $appends = ['status_text', 'status_class'];
  // Centralized status mapping
  public const STATUSES = [
    1 => ['label' => 'Active',  'class' => 'success'],
    2 => ['label' => 'Used',    'class' => 'warning'],
    3 => ['label' => 'Expired', 'class' => 'danger'],
  ];

  public static function scopeSearch($query, $search)
  {
    if (!$search) {
      return;
    }

    $query->where(function ($q) use ($search) {
      $q->where('scratch_card_code', 'like', "%{$search}%")
        ->orWhereHas('customer', function ($userQuery) use ($search) {
          $userQuery->where('users.email', 'like', "%{$search}%")
            ->orWhereRaw("CONCAT_WS(' ', users.first_name, users.middle_name, users.last_name) like ?", ["%{$search}%"]);
        });
    });
  }



  public function scratchCardReward()
  {
    return $this->belongsTo(ScratchCardReward::class);
  }

  public function customer()
  {
    return $this->belongsTo(User::class, 'customer_id');
  }

  public function order()
  {
    return $this->belongsTo(Order::class, 'order_id');
  }
  /* public function getStatusTextAttribute()
  {
    $statuses = [
      1 => 'Active',
      2 => 'Used',
      3 => 'Expired',
    ];

    return $statuses[$this->status] ?? 'N/A';
  } */
  public function getStatusTextAttribute(): string
  {
    return self::STATUSES[$this->status]['label'] ?? 'N/A';
  }

  // Accessor for CSS class
  public function getStatusClassAttribute(): string
  {
    return self::STATUSES[$this->status]['class'] ?? 'dark';
  }

  // Optional: get full mapping (useful for dropdowns)
  public static function getStatusOptions(): array
  {
    return array_map(fn($data) => $data['label'], self::STATUSES);
  }
}
