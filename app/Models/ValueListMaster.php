<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValueListMaster extends Model
{
  public function valueLists()
  {
    return $this->hasMany(ValueList::class, 'master_id', 'id');
  }

  public static function getValueList($code)
  {
    return ValueListMaster::where('code', $code)
      ->firstOrFail()
      ->valueLists()
      ->where('status', 1)
      ->get();
  }
}
