<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Vinkla\Hashids\Facades\Hashids;

class FaqCategory extends Model
{
  use SoftDeletes;

  protected $fillable = ['name', 'description', 'btn_text', 'btn_url', 'created_by', 'updated_by'];

  public function faqs()
  {
    return $this->hasMany(Faq::class);
  }

  public static function scopeSearch($query, $search)
  {
    if (!$search) return;

    $query->where(function ($q) use ($search) {
      $q->where('name', 'like', "%$search%");
    });
  }

  public static function toggleStatus(int $id = 0): JsonResponse
  {
    $update = self::find($id);
    if (!$update)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Faq Category'])]);

    $update->status = $update->status == 1 ? 0 : 1;
    $update->save();

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Faq Category Status']), 'newStatus' => $update->status]);
  }

  public static function remove(int $id = 0): JsonResponse
  {

    $blog = self::find($id);
    if (!$blog)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Faq Category'])]);

    $blog->delete();
    $blog->deleted_by = auth('admin')->id();
    $blog->save();

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Faq Category'])]);
  }

  public static function store($data, string $id = '')
  {
    $result = self::updateOrCreate(['id' => $id], [
      'name'          => $data->name,
      'description'   => $data->description ?? null,
      'btn_text'      => $data->btn_text ?? null,
      'btn_url'       => $data->btn_url ?? null,
      'updated_by'    => user('admin')->id,
      'created_by'    => $id ? self::find($id)->created_by : user('admin')->id,
    ])->id;
    if (!$result)
      return response()->json(['success' => false, 'message' => __('response.error' . ($id ? '.update' : '.create'), ['item' => 'Faq Category'])]);

    return response()->json(['success' => true, 'message' => __('response.success.' . ($id ? 'update' : 'create'), ['item' => 'Faq Category']), 'value' => Hashids::encode($result)]);
  }
}
