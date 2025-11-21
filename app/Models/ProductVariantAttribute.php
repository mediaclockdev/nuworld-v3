<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariantAttribute extends Model
{
  protected $fillable = [
    'product_variant_id',
    'attribute_id',
    'attribute_value_id',
  ];

  public function attribute()
  {
    return $this->belongsTo(ProductAttribute::class, 'attribute_id');
  }

  public function attributeValue()
  {
    return $this->belongsTo(ProductAttributeValue::class, 'attribute_value_id');
  }

  public function variant()
  {
    return $this->belongsTo(ProductVariant::class, 'product_variant_id');
  }

  public static function store($data, int $id = 0)
  {
    self::where('product_variant_id', $id)->delete();

    foreach ($data as $key => $value) {
      if (!$value) continue;
      self::create([
        'product_variant_id' => $id,
        'attribute_id' => Hashids::decode($key)[0],
        'attribute_value_id' => Hashids::decode($value)[0],
      ]);
    }

    return true;
  }
}
