<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmodulePermission extends Model
{
  protected $fillable = ['sub_module_id', 'permission_id'];

  public function subModule()
  {
    return $this->belongsTo(SubModule::class, 'sub_module_id');
  }

  public function permission()
  {
    return $this->belongsTo(Permission::class, 'permission_id');
  }
}
