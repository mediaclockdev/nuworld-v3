<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCollection extends Model
{
  protected $fillable = ['name', 'slug', 'description'];

  public function categories()
  {
    return $this->belongsToMany(ProductCategory::class, 'product_collection_category');
  }
}
