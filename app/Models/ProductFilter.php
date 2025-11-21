<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFilter extends Model
{
  protected $fillable = ['product_id', 'attribute_id'];
  public $timestamps = true;

  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  public function ProductAttribute()
  {
    return $this->belongsTo(ProductAttribute::class);
  }

  public function attribute()
  {
    return $this->belongsTo(ProductAttribute::class, 'attribute_id');
  }

  public function attributeValue()
  {
    return $this->belongsTo(ProductAttributeValue::class, 'attribute_value_id');
  }
}
