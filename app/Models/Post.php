<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Vinkla\Hashids\Facades\Hashids;

class Post extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'title',
    'slug',
    'content',
    'created_by',
    'updated_by',
    'status',
  ];

  public function blogs()
  {
    return $this->hasMany(Post::class, 'post_id');
  }

  public static function scopeSearch($query, $search)
  {
    if (!$search) return;

    $query->where(function ($q) use ($search) {
      $q->where('title', 'like', "%$search%");
    });
  }

  public static function toggleStatus(int $id = 0): JsonResponse
  {
    $update = self::find($id);
    if (!$update)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Post'])]);

    $update->status = $update->status == 1 ? 0 : 1;
    $update->save();

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Post Status']), 'newStatus' => $update->status]);
  }

  public static function remove(int $id = 0): JsonResponse
  {

    $blog = self::find($id);
    if (!$blog)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Post'])]);

    $blog->delete();
    $blog->deleted_by = auth('admin')->id();
    $blog->save();

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Post'])]);
  }


  public static function store($data, string $id = '')
  {
    $result = self::updateOrCreate(['id' => $id], [
      'title'             => $data->title,
      'slug'              => $data->slug,
      'content'           => $data->content,
      'updated_by'        => user('admin')->id,
      'created_by'        => $id ? self::find($id)->created_by : user('admin')->id,
    ])->id;
    if (!$result)
      return response()->json(['success' => false, 'message' => __('response.error' . ($id ? '.update' : '.create'), ['item' => 'Post'])]);

    return response()->json(['success' => true, 'message' => __('response.success.' . ($id ? 'update' : 'create'), ['item' => 'Post']), 'value' => Hashids::encode($result)]);
  }
}
