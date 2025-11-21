<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;

class PaymentSettings extends Model
{
  use SoftDeletes;
  protected $fillable = [
    'gateway_name',
    'gateway_key',
    'gateway_secret',
    'gateway_mode',
    'gateway_other',
    'status',
    'created_by',
    'updated_by',
  ];

  public static function store($data, int $id = 0): JsonResponse
  {
    $result = PaymentSettings::updateOrCreate(
      ['id' => $id],
      [
        'gateway_name' => $data->gatewayname,
        'gateway_key' => $data->gatewaykey,
        'gateway_secret' => $data->gatewaysecret,
        'gateway_mode' => $data->gatewaymode,
        'gateway_other' => $data->other_info,
        'status' => $data->status,
        'updated_by' => user('admin')->id,
        'created_by' => $id ? self::find($id)->created_by : user('admin')->id
      ]
    );

    if ($result)
      return response()->json(['success' => true, 'message' => __('response.success' . ($id ? '.update' : '.create'), ['item' => 'Payment Option'])]);

    return response()->json(['success' => false, 'message' => __('response.error' . ($id ? '.update' : '.create'), ['item' => 'Payment Option'])]);
  }

  public static function remove(int $id = 0): JsonResponse
  {
    $data = self::find($id);
    if (!$data)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Payment Option'])]);

    $data->delete();
    $data->deleted_by = auth('admin')->id();
    $data->save();

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Payment Option'])]);
  }

  public static function toggleStatus(int $id = 0)
  {
    $update = self::find($id);
    if (!$update)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Payment Option'])]);

    $update->status = $update->status == 1 ? 0 : 1;
    $update->updated_by = auth('admin')->id();
    $update->save();

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Payment Option Status']), 'newStatus' => $update->status]);
  }

  public static function scopeSearch($query, $search)
  {
    return $query->where('gateway_name', 'like', '%' . $search . '%')
      ->orWhere('gateway_key', 'like', '%' . $search . '%')
      ->orWhere('gateway_mode', 'like', '%' . $search . '%');
  }
}
