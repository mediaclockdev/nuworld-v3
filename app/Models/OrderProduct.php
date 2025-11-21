<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProduct extends Model
{
  use SoftDeletes;

  protected $table = 'order_products';

  protected $fillable = [
    'order_id',
    'order_item_uid',
    'variant_id',
    'quantity',
    'promotion_id',
    'sku',
    'regular_price',
    'sell_price',
    'tax_amount',
    'status',
    'created_by',
    'updated_by',
    'deleted_by',
  ];

  // Relationships
  public function order()
  {
    return $this->belongsTo(Order::class);
  }

  public function variant()
  {
    return $this->belongsTo(ProductVariant::class, 'variant_id');
  }

  public function review()
  {
    return $this->hasOne(ProductReview::class, 'variant_id', 'variant_id')
      ->where('user_id', auth()->id());
  }

  public function requestItems()
  {
    return $this->hasMany(OrderReturnItem::class);
  }

  public function scopeSearch($query, $search)
  {
    return $query->where('sku', 'like', '%' . $search . '%')
      ->orWhereHas('variant', function ($q) use ($search) {
        $q->where('name', 'like', '%' . $search . '%')
          ->orWhereHas('product', function ($q2) use ($search) {
            $q2->whereHas('category', function ($q3) use ($search) {
              $q3->where('title', 'like', '%' . $search . '%');
            });
          });
      });
  }
}
