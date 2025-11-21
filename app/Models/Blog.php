<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
  use SoftDeletes, HasFactory;

  protected $fillable = [
    'title',
    'slug',
    'post_id',
    'image',
    'short_description',
    'long_description',
    'status',
    'published_at',
    'created_by',
    'updated_by',
  ];

  public function post()
  {
    return $this->belongsTo(Post::class);
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
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Blog'])]);

    $update->status = $update->status == 1 ? 0 : 1;
    $update->save();

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Blog Status']), 'newStatus' => $update->status]);
  }

  public static function remove(int $id = 0): JsonResponse
  {

    $blog = self::find($id);
    if (!$blog)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Blog'])]);

    $blog->delete();
    $blog->deleted_by = auth('admin')->id();
    $blog->save();

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Blog'])]);
  }


  public static function store($data, string $id = '')
  {
    // dd($data);
    $result = self::updateOrCreate(['id' => $id], [
      'title'             => $data->title,
      'slug'              => $data->slug,
      'post_id'           => $data->post_id,
      'image'             => $data->image_name ?? null,
      'short_description' => $data->short_description,
      'long_description'  => $data->long_description,
      'published_at'      => $data->published_at ?? null,
      'updated_by'        => user('admin')->id,
      'created_by'        => $id ? self::find($id)->created_by : user('admin')->id,
    ])->id;
    if (!$result)
      return response()->json(['success' => false, 'message' => __('response.error' . ($id ? '.update' : '.create'), ['item' => 'Blog'])]);

    return response()->json(['success' => true, 'message' => __('response.success.' . ($id ? 'update' : 'create'), ['item' => 'Blog']), 'value' => Hashids::encode($result)]);
  }
}
