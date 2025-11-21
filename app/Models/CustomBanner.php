<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class CustomBanner extends Model
{
  use SoftDeletes;

  protected $guarded = [];

  public static function remove($id): JsonResponse
  {
    $banner = CustomBanner::findOrFail($id);

    $settings = json_decode($banner->settings, true);

    if (!empty($settings['image']))
      Storage::disk('public')->delete('uploads/banners/' . $settings['image']);

    $banner->delete();

    return response()->json(['status' => true, 'message' => 'Banner deleted successfully']);
  }
}
