<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPortrait extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'user_id',
    'gender',
    'original_image',
    'processed_image',
    'thumbnail',
    'width',
    'height',
    'aspect_ratio',
    'status',
    'created_by',
    'updated_by',
    'deleted_by',
  ];

  protected $casts = [
    'status' => 'boolean',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
