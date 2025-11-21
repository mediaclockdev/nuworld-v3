<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Vinkla\Hashids\Facades\Hashids;

class ScratchCardReward extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'type',
    'value',
    'coupon_code',
    'conditions',
    'valid_from',
    'valid_to',
    'validity_period',
    'created_by',
    'updated_by',
  ];

  protected $casts = [
    'conditions'    => 'array',
    'valid_from'    => 'date',
    'valid_to'      => 'date',
  ];

  public function customerRewards()
  {
    return $this->hasMany(CustomerReward::class);
  }

  public static function scopeSearch($query, $search)
  {
    if (!$search) return;

    $query->where(function ($q) use ($search) {
      $q->where('type', 'like', "%$search%")
        ->orWhere('value', 'like', "%$search%")
        ->orWhere('conditions', 'like', "%$search%");
    });
  }

  public static function toggleStatus(int $id = 0): JsonResponse
  {
    $reward = self::find($id);
    if (!$reward) {
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Scratch Card Reward'])]);
    }

    $reward->status = $reward->status == 1 ? 0 : 1;
    $reward->save();

    return response()->json([
      'success' => true,
      'message' => __('response.success.update', ['item' => 'Scratch Card Reward Status']),
      'newStatus' => $reward->status
    ]);
  }

  public static function store($data, string $id = '')
  {
    $result = self::updateOrCreate(['id' => $id], [
      'type'            => $data->type,
      'value'           => $data->value,
      'coupon_code'     => $data->coupon_code,
      'conditions'      => $data->conditions,
      'valid_from'      => $data->valid_from,
      'valid_to'        => $data->valid_to,
      'validity_period' => $data->validity_period,
      'updated_by'      => user('admin')->id,
      'deleted_by'      => user('admin')->id,
      'created_by'      => $id ? self::find($id)->created_by : user('admin')->id,
    ])->id;

    if (!$result) {
      return response()->json([
        'success' => false,
        'message' => __('response.error.' . ($id ? 'update' : 'create'), ['item' => 'Scratch Card Reward'])
      ]);
    }

    return response()->json([
      'success' => true,
      'message' => __('response.success.' . ($id ? 'update' : 'create'), ['item' => 'Scratch Card Reward']),
      'value' => Hashids::encode($result)
    ]);
  }

  public static function remove(int $id = 0): JsonResponse
  {

    $scratchCardReward = self::find($id);
    if (!$scratchCardReward)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Scratch Card Reward'])]);

    $scratchCardReward->delete();
    $scratchCardReward->deleted_by = auth('admin')->id();
    $scratchCardReward->save();

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Scratch Card Reward'])]);
  }
}
