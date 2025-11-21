<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
  protected $fillable = [
    'product_id',
    'product_variant_id',
    'quantity',
    'threshold',
    'max_selling_quantity',
    'alert_role_id',
    'created_by',
    'updated_by'
  ];


  public function inventoryHistories()
  {
    return $this->hasMany(InventoryHistory::class, 'inventory_id');
  }

  public function product()
  {
    return $this->belongsTo(Product::class, 'product_id', 'id');
  }
  public function variant()
  {
    return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
  }
  public static function store($data, $productID, $variantID)
  {
    // 1. Get existing inventory (if any)
    $existingInventory = self::where('product_id', $productID)
      ->where('product_variant_id', $variantID)
      ->first();

    $oldStock = $existingInventory?->quantity ?? 0;

    // 2. Update or create inventory
    $inventory = self::updateOrCreate(
      ['product_id' => $productID, 'product_variant_id' => $variantID],
      [
        'quantity' => $data['stock'],
        'threshold' => $data['threshold'] ?? 0,
        'max_selling_quantity' => $data['max_selling_quantity'] ?? 0,
        'alert_role_id' => user('admin')->role_id,
        'updated_by' => user('admin')->id,
        'created_by' => $existingInventory?->created_by ?? user('admin')->id,
      ]
    );

    // 3. Insert inventory history using relationship
    $inventory->inventoryHistories()->create([
      'old_stock' => $oldStock,
      'new_stock' => $data['stock'],
      'created_by' => user('admin')->id,
      'updated_by' => user('admin')->id,
    ]);

    return $inventory->id;
  }


  public static function scopeSearch($query, $search)
  {
    return $query->whereHas('product', function ($q) use ($search) {
      $q->where('name', 'like', '%' . $search . '%');
    })
      ->orWhereHas('variant', function ($q) use ($search) {
        $q->where('name', 'like', '%' . $search . '%')
          ->orWhere('sku', 'like', '%' . $search . '%');
      });
  }
}
