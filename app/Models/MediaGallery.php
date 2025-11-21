<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaGallery extends Model
{
  protected $fillable = [
    'title',
    'file_name',
    'file_type',
    'created_by',
  ];


  public static function store($title, $filename, $filetype)
  {
    return self::create([
      'title' => $title ?? '',
      'file_name' => $filename,
      'file_type' => $filetype,
      'created_by' => user('admin')->id
    ])->id;
  }
}
