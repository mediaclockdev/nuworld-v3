<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Vinkla\Hashids\Facades\Hashids;

class Faq extends Model
{
  use SoftDeletes;

  protected $fillable = ['faq_category_id', 'question', 'answer', 'created_by', 'updated_by'];

  public function faqCategory()
  {
    return $this->belongsTo(FaqCategory::class, 'faq_category_id');
  }
  public static function scopeSearch($query, $search)
  {
    if (!$search) return;

    $query->where(function ($q) use ($search) {
      $q->where('question', 'like', "%$search%")
        ->orWhereHas('faqCategory', function ($q2) use ($search) {
          $q2->where('name', 'like', "%$search%");
        });
    });
  }

  public static function toggleStatus(int $id = 0): JsonResponse
  {
    $update = self::find($id);
    if (!$update)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Faq'])]);

    $update->status = $update->status == 1 ? 0 : 1;
    $update->save();

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Faq Status']), 'newStatus' => $update->status]);
  }

  public static function remove(int $id = 0): JsonResponse
  {

    $blog = self::find($id);
    if (!$blog)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Faq'])]);

    $blog->delete();
    $blog->deleted_by = auth('admin')->id();
    $blog->save();

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Faq'])]);
  }


  public static function store($data, string $id = '')
  {
    // dd($data);
    $result = self::updateOrCreate(['id' => $id], [
      'question'          => $data->question,
      'faq_category_id'   => $data->faq_category_id,
      'answer'            => $data->answer,
      'updated_by'        => user('admin')->id,
      'created_by'        => $id ? self::find($id)->created_by : user('admin')->id,
    ])->id;
    if (!$result)
      return response()->json(['success' => false, 'message' => __('response.error' . ($id ? '.update' : '.create'), ['item' => 'Faq'])]);

    return response()->json(['success' => true, 'message' => __('response.success.' . ($id ? 'update' : 'create'), ['item' => 'Faq']), 'value' => Hashids::encode($result)]);
  }
}
