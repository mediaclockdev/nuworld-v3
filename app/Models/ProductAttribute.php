<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;

class ProductAttribute extends Model
{
  use SoftDeletes;

  protected $fillable = ['name', 'sequence', 'status', 'created_by', 'updated_by'];
  public function values()
  {
    return $this->hasMany(ProductAttributeValue::class, 'attribute_id', 'id');
  }

  public static function store($request, $id = 0): JsonResponse
  {
    $attribute = self::updateOrCreate(
      ['id' => $id],
      [
        'name' => $request->attribute_title,
        'sequence' => $request->sequence,
        'status' => $request->status ?? 0,
        'updated_by' => user('admin')->id,
        'created_by' => $id ? self::find($id)->created_by : user('admin')->id,
      ]
    );

    if (!$attribute) {
      return response()->json(['success' => false, 'message' => __('response.error' . ($id ? '.update' : '.create'), ['item' => 'Attribute'])]);
    }

    return response()->json(['success' => true, 'message' => __('response.success.' . ($id ? 'update' : 'create'), ['item' => 'Attribute'])]);
  }

  public static function remove($id = 0): JsonResponse
  {
    $attribute = self::find($id);

    if (!$attribute)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Attribute'])]);

    if (ProductAttributeValue::where('attribute_id', $id)->exists()) {
      return response()->json(['success' => false, 'message' => __('response.error.has_associated', ['item1' => 'Attribute', 'item2' => 'Attribute Value'])]);
    }

    $attribute->delete();

    $attribute->deleted_by = auth('admin')->id();
    $attribute->save();


    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Attribute'])]);
  }

  public static function toggleStatus($id = 0): JsonResponse
  {
    $update = self::find($id);

    if (!$update)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Attribute'])]);

    $update->status = $update->status == 1 ? 0 : 1;
    $update->updated_by = auth('admin')->id();
    $update->save();

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Attribute Status']), 'newStatus' => $update->status]);
  }

  public function scopeSearch($query, $value)
  {
    return $query->where('name', 'like', '%' . $value . '%')
      ->orWhere('sequence', 'like', '%' . $value . '%');
  }
}
