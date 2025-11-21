<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'user_id',
    'product_variant_id',
    'quantity',
    'is_saved_for_later',
    'status',
    'created_by',
    'updated_by',
    'deleted_by'
  ];


  public function productVariant()
  {
    return $this->belongsTo(ProductVariant::class, 'product_variant_id');
  }

  public function userDetails()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public static function scopeSearch($query, $search)
  {
    if (!$search) return;

    $query->where(function ($q) use ($search) {
      $q->whereHas('userDetails', function ($q2) use ($search) {
        $q2->where('first_name', 'like', "%$search%")
          ->orWhere('last_name', 'like', "%$search%")
          ->orWhere('middle_name', 'like', "%$search%")
          ->orWhere('email', 'like', "%$search%");
      })
        ->orWhereHas('productVariant', function ($q2) use ($search) {
          $q2->where('name', 'like', "%$search%");
        });
    });
  }
}
