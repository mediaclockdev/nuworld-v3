<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Http\JsonResponse;

class ProductRecommendation extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'product_id',
    'product_variant_id',
    'description',
    'valid_from',
    'valid_to',
    'created_by',
    'updated_by',
    'deleted_by'
  ];

  protected $casts = [
    // 'valid_from'    => 'date',
    // 'valid_to'      => 'date',
    // 'is_active'     => 'boolean',
    // 'discount_amount' => 'float',
    // 'max_discount'    => 'float',
    // 'min_order_value' => 'float',
    // 'max_uses'        => 'integer',
    // 'per_user_limit'  => 'integer',
  ];

  public function getValidFromAttribute($value)
  {
    return Carbon::parse($value)->format('d/m/Y');
  }

  public function getValidToAttribute($value)
  {
    return Carbon::parse($value)->format('d/m/Y');
  }

  public function product()
  {
    return $this->belongsTo(Product::class, 'product_id', 'id');
  }
  public function variant()
  {
    return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
  }

  // --------------------------
  // Store or Update
  // --------------------------
  public static function store($request, $id = null): JsonResponse
  {
    $adminId = user('admin')->id;
    $productId = Hashids::decode($request->product_id)[0];
    $variantIds = $request->product_variant_id;

    $success = true;

    foreach ($variantIds as $variantId) {
      $data = [
        'description' => $request->description,
        'created_by'  => $adminId,
        'updated_by'  => $adminId,
      ];

      $match = [
        'product_id' => $productId,
        'product_variant_id' => $variantId,
      ];

      if (!self::updateOrCreate($match, $data)) {
        $success = false;
      }
    }

    return response()->json([
      'success' => $success,
      'message' => __(
        $success ? 'response.success.' . ($id ? 'update' : 'create') : 'response.error.' . ($id ? 'update' : 'create'),
        ['item' => 'Base Seller']
      ),
    ]);
  }





  // --------------------------
  // Toggle Status
  // --------------------------
  public static function toggleStatus($id)
  {
    $update = self::find($id);

    if (!$update)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Promotion'])]);

    $update->status = $update->status == 1 ? 0 : 1;
    $update->updated_by = auth('admin')->id();
    $update->save();

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Promotion']), 'newStatus' => $update->status]);
  }

  public function getPromotionModeLabelAttribute()
  {
    return $this->promotion_mode == 1 ? 'Product Wise' : 'Category Wise';
  }

  // --------------------------
  // Soft Delete
  // --------------------------
  // public static function remove(int $id): JsonResponse
  // {
  //   $coupon = self::find($id);

  //   if (!$coupon) {
  //     return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Coupon'])]);
  //   }

  //   $coupon->deleted_by = user('admin')->id;
  //   $coupon->save();
  //   $coupon->delete();

  //   return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Coupon'])]);
  // }

  // --------------------------
  // Local Scopes
  // --------------------------
  public static function scopeSearch($query, $search)
  {
    return $query->whereHas('product', function ($q) use ($search) {
      $q->where('name', 'like', '%' . $search . '%');
    })
      ->orWhereHas('variant', function ($q) use ($search) {
        $q->where('name', 'like', '%' . $search . '%')
          ->orWhere('sku', 'like', '%' . $search . '%');
      });
  }
}
