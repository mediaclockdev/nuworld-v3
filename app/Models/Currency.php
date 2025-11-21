<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;

class Currency extends Model
{
  use SoftDeletes;
  protected $fillable = ['name', 'symbol', 'code', 'position', 'decimal_places', 'status', 'created_by', 'updated_by'];

  public static function store($request, $id = ''): JsonResponse
  {
    $update = self::updateOrCreate(
      ['id' => $id],
      [
        'name' => $request->currencyname,
        'symbol' => $request->currencysymbol,
        'code' => $request->currencycode,
        'position' => $request->currencyposition ?? 'Left',
        'decimal_places' => $request->decimal_places ?? 0,
        'status' => $request->status ?? 1,
        'updated_by' => user('admin')->id,
        'created_by' => $id ? self::find($id)->created_by : user('admin')->id
      ]
    );

    if ($update)
      return response()->json(['success' => true, 'message' => __('response.success' . ($id ? '.update' : '.create'), ['item' => 'Currency'])]);

    return response()->json(['success' => false, 'message' => __('response.error' . ($id ? '.update' : '.create'), ['item' => 'Currency'])]);
  }

  public static function toggleStatus(int $id = 0): JsonResponse
  {
    $update = self::find($id);
    if (!$update)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Currency'])]);

    $update->status = $update->status == 1 ? 0 : 1;
    $update->save();

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Currency Status']), 'newStatus' => $update->status]);
  }

  public static function remove(int $id = 0): JsonResponse
  {
    $remove = self::find($id);

    if (!$remove)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Currency'])]);

    $remove->delete();
    $remove->deleted_by = auth('admin')->id();
    $remove->save();

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Currency'])]);
  }

  public static function scopeSearch($query, $search)
  {
    return $query->where('name', 'like', '%' . $search . '%')
      ->orWhere('code', 'like', '%' . $search . '%')
      ->orWhere('symbol', 'like', '%' . $search . '%')
      ->orWhere('position', 'like', '%' . $search . '%');
  }
}
