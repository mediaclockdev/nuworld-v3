<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryHistory extends Model
{


  protected $table = 'inventory_histories';

  protected $guarded = [];

  // Relationships
  public function inventoryDetails()
  {
    return $this->belongsTo(Inventory::class, 'inventory_id');
  }
}
