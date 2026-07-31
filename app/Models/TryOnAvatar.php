<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TryOnAvatar extends Model
{
  use SoftDeletes;
  protected $table = 'tryon_avatars';

  protected $fillable = [
    'name',
    'image',
    'supported_regions',
    'is_default',
    'status',
    'created_by',
    'updated_by',
    'deleted_by',
  ];

  protected $casts = [
    'supported_regions' => 'array',
    'is_default' => 'boolean',
    'status' => 'boolean',
  ];
}
