<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Vinkla\Hashids\Facades\Hashids;

class Banner extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'image',
    'title',
    'alt_text',
    'hyper_link',
    'sequence',
    'content',
    'position',
    'status',
    'extra_value',
    'created_by',
    'updated_by',
  ];

  public function positionValue()
  {
    return $this->belongsTo(ValueList::class, 'position');
  }


  public static function store($request, int $id = 0)
  {
    $banner = self::updateOrCreate(['id' => $id], [
      'image' => $request->image_name,
      'title' => $request->banner_title ? trim($request->banner_title) : null,
      'alt_text' => $request->banner_alt_text ? trim($request->banner_alt_text) : null,
      'hyper_link' => $request->banner_hyperlink ?? null,
      'sequence' => $request->banner_sequence ?? 1,
      'content' => $request->banner_description ? trim($request->banner_description) : null,
      'position' => Hashids::decode($request->banner_position)[0] ?? 0,
      'extra_value' => $request->banner_extra_value ? trim($request->banner_extra_value) : null,
      'updated_by' => user('admin')->id,
      'created_by' => $id ? self::find($id)->created_by : user('admin')->id,
      'status' => $request->banner_status ?? 1
    ]);

    return response()->json([
      'success' => true,
      'message' => __('response.success.' . ($id ? 'update' : 'create'), ['item' => 'Banner']),
    ]);
  }

  public static function remove(int $id = 0): JsonResponse
  {
    $banner = self::find($id);
    if (!$banner)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Banner'])]);

    $banner->delete();
    $banner->deleted_by = auth('admin')->id();
    $banner->save();

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Banner'])]);
  }

  public static function toggleStatus(int $id = 0): JsonResponse
  {
    $search = self::find($id);
    if (!$search)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Banner']),]);

    $search->status = $search->status ? 0 : 1;
    $search->updated_by = auth('admin')->id();
    $search->save();

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Banner Status']), 'newStatus' => $search->status]);
  }

  public function scopeSearch($query, $value)
  {
    return $query->where('title', 'like', '%' . $value . '%')
      ->orWhere('position', 'like', '%' . $value . '%')
      ->orWhere('hyper_link', 'like', '%' . $value . '%')
      ->orWhere('sequence', 'like', '%' . $value . '%');
  }
}
