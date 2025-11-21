<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Http\JsonResponse;

class Promotion extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'name',
    'promotion_start_from',
    'promotion_end_to',
    'description',
    'promotion_mode',
    'status',
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

  public function promotionDetail()
  {
    return $this->hasMany(PromotionDetail::class, 'promotion_id');
  }

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
    $data = [
      'name'                 => $request->name,
      'promotion_start_from' => Carbon::createFromFormat('Y-m-d h:i A', $request->promotion_start_from)->format('Y-m-d H:i:s'),
      'promotion_end_to'     => Carbon::createFromFormat('Y-m-d h:i A', $request->promotion_end_to)->format('Y-m-d H:i:s'),
      'description'         => $request->description,
      'promotion_mode'      => $request->promotion_mode,
      'status'              => $request->status ?? true,
      'updated_by'          => user('admin')->id,
    ];

    if (!$id) {
      $data['created_by'] = user('admin')->id;
    } else {
      $data['created_by'] = self::find($id)->created_by ?? user('admin')->id;
    }

    $promotion = self::updateOrCreate(['id' => $id], $data);
    //dd($promotion, $request->all());
    // Save promotion details (product + variants)
    if ($promotion) {
      // Clear old details if updating
      if ($id) {
        $promotion->promotionDetail()->delete();
      }

      //$productId = $request->product_id;
      $productId = Hashids::decode($request->product_id)[0];
      //dd($productId);
      $variantIds = $request->product_variant_id;
      //dd($variantIds);
      // Remove any invalid/null variant IDs
      //$variantIds = array_filter($variantIds);
      //dd($variantIds);
      if (is_array($variantIds)) {
        foreach ($variantIds as $variantId) {
          $promotion->promotionDetail()->create([
            'promotion_id' => $promotion->id,
            'product_id' => $productId,
            'product_variant_id' => $variantId,
            'type' => $request->type,
            'discount_amount' => $request->discount_amount,
            // 'valid_from' => $request->valid_from,
            // 'valid_to' => $request->valid_to,
          ]);
        }
      }
    }

    return response()->json([
      'success' => (bool) $promotion,
      'message' => __($promotion ? 'response.success.' . ($id ? 'update' : 'create') : 'response.error.' . ($id ? 'update' : 'create'), ['item' => 'Promotion']),
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
  public function scopeSearch($query, $term)
  {
    $term = trim($term);

    if ($term) {
      $query->where(function ($q) use ($term) {
        $q->where('name', 'LIKE', "%{$term}%");
        // ->orWhere('description', 'LIKE', "%{$term}%");

        // Add promotion_mode string matching
        if (stripos('Product Wise', $term) !== false) {
          $q->orWhere('promotion_mode', 1);
        } elseif (stripos('Category Wise', $term) !== false) {
          $q->orWhere('promotion_mode', 2);
        }
      });
    }

    return $query;
  }
}
