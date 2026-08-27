<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDevice extends Model
{
  protected $fillable = [
    'user_id',
    'device_id',
    'fcm_token',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
