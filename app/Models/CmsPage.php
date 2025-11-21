<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;

class CmsPage extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'title',
    'slug',
    'body',
    'meta_title',
    'meta_keywords',
    'meta_description',
    'feature_image',
    'status',
    'created_by',
    'updated_by',
  ];

  public static function store($request, int $id = 0): JsonResponse
  {
    $cmsPage = self::updateOrCreate(['id' => $id], [
      'title'             => $request->cms_title,
      'slug'              => $request->cms_slug ?? null,
      'body'              => $request->cms_description ?? null,
      'meta_title'        => $request->meta_title ?? null,
      'meta_keywords'     => $request->meta_keywords ?? null,
      'meta_description'  => $request->meta_description ?? null,
      'feature_image'     => $request->image_name,
      'status'            => $request->cms_status ?? 1,
      'updated_by'        => user('admin')->id,
      'created_by'        => $id ? self::find($id)->created_by : user('admin')->id,
    ]);

    return response()->json([
      'success' => true,
      'message' => __('response.success.' . ($id ? 'update' : 'create'), ['item' => 'CMS Page']),
    ]);
  }

  public static function remove(int $id = 0): JsonResponse
  {
    $cmsPage = self::find($id);

    if (!$cmsPage)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'CMS Page'])]);

    $cmsPage->delete();
    $cmsPage->deleted_by = auth('admin')->id();
    $cmsPage->save();

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'CMS Page'])]);
  }

  public static function toggleStatus(int $id = 0): JsonResponse
  {
    $search = self::find($id);
    if (!$search)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'CMS page']),]);

    $search->status = $search->status ? 0 : 1;
    $search->updated_by = auth('admin')->id();
    $search->save();

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'CMS page Status']), 'newStatus' => $search->status]);
  }

  public function scopeSearch($query, $value)
  {
    return $query->where('title', 'like', '%' . $value . '%')
      ->orWhere('slug', 'like', '%' . $value . '%')
      ->orWhere('meta_title', 'like', '%' . $value . '%');
  }
}
