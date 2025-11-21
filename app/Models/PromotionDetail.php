<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PromotionDetail extends Model
{
  //
  use SoftDeletes;
  protected $guarded = [];

  public function promotion()
  {
    return $this->belongsTo(Promotion::class, 'promotion_id');
  }
}
