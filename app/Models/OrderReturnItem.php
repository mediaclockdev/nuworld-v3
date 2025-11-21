<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReturnItem extends Model
{
  protected $fillable = [
    'order_request_id',
    'order_item_id',
    'quantity',
    'reason',
    'status',
  ];

  // Relationships
  public function orderRequest()
  {
    return $this->belongsTo(OrderReturn::class);
  }

  public function orderItem()
  {
    return $this->belongsTo(OrderProduct::class);
  }
}
