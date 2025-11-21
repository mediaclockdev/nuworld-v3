<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;

class Coupon extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'code',
    'type',
    'discount_amount',
    'max_discount',
    'min_order_value',
    'max_uses',
    'per_user_limit',
    'is_active',
    'valid_from',
    'valid_to',
    'created_by',
    'updated_by',
    'deleted_by'
  ];

  protected $casts = [
    'valid_from'    => 'date',
    'valid_to'      => 'date',
    'is_active'     => 'boolean',
    'discount_amount' => 'float',
    'max_discount'    => 'float',
    'min_order_value' => 'float',
    'max_uses'        => 'integer',
    'per_user_limit'  => 'integer',
  ];

  public function getValidFromAttribute($value)
  {
    return Carbon::parse($value)->format('d/m/Y');
  }

  public function getValidToAttribute($value)
  {
    return Carbon::parse($value)->format('d/m/Y');
  }

  // --------------------------
  // Store or Update
  // --------------------------
  public static function store($request, $id = null): JsonResponse
  {
    if ($id) {
      $existingCoupon = self::findOrFail($id);
      $request->merge(['code' => $existingCoupon->code]);
    }

    $coupon = self::updateOrCreate(
      ['id' => $id],
      [
        'code'             => $request->code,
        'type'             => $request->type,
        'discount_amount'  => $request->discount_amount ?? 0,
        'max_discount'     => $request->max_discount,
        'min_order_value'  => $request->min_order_value ?? 0,
        'max_uses'         => $request->max_uses,
        'per_user_limit'   => $request->per_user_limit ?? 1,
        'is_active'        => $request->is_active ?? true,
        'valid_from'       => $request->valid_from,
        'valid_to'         => $request->valid_to,
        'updated_by'       => user('admin')->id,
        'created_by'       => $id ? self::find($id)->created_by ?? user('admin')->id : user('admin')->id
      ]
    );

    return response()->json([
      'success' => (bool) $coupon,
      'message' => __($coupon ? 'response.success.' . ($id ? 'update' : 'create') : 'response.error.' . ($id ? 'update' : 'create'), ['item' => 'Coupon'])
    ]);
  }

  // --------------------------
  // Toggle Status
  // --------------------------
  public static function toggleStatus(int $id): JsonResponse
  {
    $coupon = self::find($id);

    if (!$coupon) {
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Coupon'])]);
    }

    $coupon->is_active = !$coupon->is_active;
    $coupon->save();

    return response()->json([
      'success'    => true,
      'message'    => __('response.success.update', ['item' => 'Coupon Status']),
      'newStatus'  => $coupon->is_active
    ]);
  }

  // --------------------------
  // Soft Delete
  // --------------------------
  public static function remove(int $id): JsonResponse
  {
    $coupon = self::find($id);

    if (!$coupon) {
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Coupon'])]);
    }

    $coupon->deleted_by = user('admin')->id;
    $coupon->save();
    $coupon->delete();

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Coupon'])]);
  }

  // --------------------------
  // Local Scopes
  // --------------------------
  public function scopeSearch($query, $search)
  {
    return $query->where(function ($q) use ($search) {
      $q->where('code', 'like', '%' . $search . '%')
        ->orWhere('type', 'like', '%' . $search . '%')
        ->orWhere('discount_amount', 'like', '%' . $search . '%')
        ->orWhere('max_discount', 'like', '%' . $search . '%')
        ->orWhere('min_order_value', 'like', '%' . $search . '%')
        ->orWhere('min_order_value', 'like', '%' . $search . '%')
        ->orWhere('per_user_limit', 'like', '%' . $search . '%');
    });
  }
}
