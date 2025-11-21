<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;

class Support extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'message',
  ];

  public static function scopeSearch($query, $search)
  {
    if (!$search) return;

    $query->where(function ($q) use ($search) {
      $q->whereRaw("CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) LIKE '%$search%'");
      $q->orWhere('email', 'like', "%$search%");
    });
  }
  public static function remove(int $id = 0): JsonResponse
  {

    $blog = self::find($id);
    if (!$blog)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Contact'])]);

    $blog->delete();
    $blog->save();

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Contact'])]);
  }
}
