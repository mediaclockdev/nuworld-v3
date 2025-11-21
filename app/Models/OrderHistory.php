<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{


  protected $table = 'order_histories';

  protected $fillable = [
    'order_id',
    'scheduled_date',
    'scheduled_time',
    'description',
    'created_by',
    'status',
    'updated_by'
  ];

  // Relationships
  public function order()
  {
    return $this->belongsTo(Order::class);
  }
}
