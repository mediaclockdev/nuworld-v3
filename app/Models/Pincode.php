<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Vinkla\Hashids\Facades\Hashids;

class Pincode extends Model
{
  protected $fillable = [
    'code',
    'estimate_days',
    'status',
  ];

  public static function scopeSearch($query, $search)
  {
    if (!$search) return;

    $query->where(function ($q) use ($search) {
      $q->where('code', 'like', "%$search%");
    });
  }

  public static function toggleStatus(int $id = 0): JsonResponse
  {
    $update = self::find($id);
    if (!$update)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Pincode'])]);

    $update->status = $update->status == 1 ? 0 : 1;
    $update->save();

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Pincode Status']), 'newStatus' => $update->status]);
  }

  public static function store($data, string $id = '')
  {
    $result = self::updateOrCreate(['id' => $id], [
      'code'          => $data->code,
      'estimate_days' => $data->estimate_days,
    ])->id;
    if (!$result)
      return response()->json(['success' => false, 'message' => __('response.error' . ($id ? '.update' : '.create'), ['item' => 'Pincode'])]);

    return response()->json(['success' => true, 'message' => __('response.success.' . ($id ? 'update' : 'create'), ['item' => 'Pincode']), 'value' => Hashids::encode($result)]);
  }
}
