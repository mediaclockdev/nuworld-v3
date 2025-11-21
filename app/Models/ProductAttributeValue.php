<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Vinkla\Hashids\Facades\Hashids;

class ProductAttributeValue extends Model
{
  use SoftDeletes;

  protected $fillable = ['attribute_id', 'value', 'value_details', 'sequence', 'status', 'created_by', 'updated_by'];
  public function attribute()
  {
    return $this->belongsTo(ProductAttribute::class, 'attribute_id', 'id');
  }

  public static function store($request, $id = 0): JsonResponse
  {
    $chkPrevValue = self::where('attribute_id', Hashids::decode($request->parent_attribute)[0])->where([['id', '!=', $id], ['value', $request->value_name], ['deleted_at', null]])->exists();

    if ($chkPrevValue)
      return response()->json(['success' => false, 'message' => __('response.error.duplicate', ['item' => 'Attribute Value'])]);

    $attributeValue =  self::updateOrCreate(
      ['id' => $id],
      [
        'attribute_id' => Hashids::decode($request->parent_attribute)[0],
        'value' => $request->value_name,
        'value_details' => $request->extra_details ?? null,
        'sequence' => $request->sequence,
        'status' => $request->status ?? 0,
        'updated_by' => user('admin')->id,
        'created_by' => $id ? self::find($id)->created_by : user('admin')->id,
      ]
    );

    if (!$attributeValue)
      return response()->json(['success' => false, 'message' => __('response.error' . ($id ? '.update' : '.create'), ['item' => 'Attribute Value'])]);

    return response()->json(['success' => true, 'message' => __('response.success.' . ($id ? 'update' : 'create'), ['item' => 'Attribute Value'])]);
  }

  public static function remove($id = 0): JsonResponse
  {
    $attributeValue = self::find($id);

    if (!$attributeValue)
      return response()->json(['success' => false, 'message' => __('response.error.delete', ['item' => 'Attribute Value'])]);

    $productVariant = ProductVariantAttribute::where('attribute_value_id', $id)->exists();

    if ($productVariant)
      return response()->json(['success' => false, 'message' => __('response.error.has_associated', ['item1' => 'Attribute Value', 'item2' => 'Product Variant'])]);

    $attributeValue->delete();
    $attributeValue->deleted_by = auth('admin')->id();
    $attributeValue->save();

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Attribute Value'])]);
  }

  public static function toggleStatus($id = 0): JsonResponse
  {
    $update = self::find($id);

    if (!$update)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Attribute Value'])]);

    $update->status = !$update->status;
    $update->updated_by = auth('admin')->id();
    $update->save();

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Attribute Value Status']), 'newStatus' => $update->status]);
  }

  public function scopeSearch($query, $value)
  {
    return $query->where('value', 'like', '%' . $value . '%')
      ->orWhere('sequence', 'like', '%' . $value . '%');
  }
}
