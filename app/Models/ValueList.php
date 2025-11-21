<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValueList extends Model
{
  public function scopeBannerPositions($query)
  {
    return $query->where('master_id', ValueListMaster::where('code', 'BANNER-POSITION')->first()->id);
  }
}
