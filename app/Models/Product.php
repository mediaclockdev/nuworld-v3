<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;

class Product extends Model
{
  use SoftDeletes;
  protected $fillable = ['category_id', 'type', 'name', 'description', 'product_details', 'specifications', 'care_maintenance', 'warranty', 'sku',  'status', 'created_by', 'updated_by'];

  public function category()
  {
    return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
  }

  public function variants()
  {
    return $this->hasMany(ProductVariant::class, 'product_id');
  }

  public static function store($data, int $id = 0)
  {
    $result = self::updateOrCreate(['id' => $id], [
      'category_id' => $data->category_id,
      'name' => $data->product_name,
      'description' => $data->product_desc ?? '',
      'product_details' => $data->product_details ?? '',
      'specifications' => $data->specifications ?? '',
      'care_maintenance' => $data->care_maintenance ?? '',
      'warranty' => $data->warranty ?? '',
      'sku' => $data->SKU,
      'status' => $data->status ?? 1,
      'updated_by' => user('admin')->id,
      'created_by' => $id ? self::find($id)->created_by : user('admin')->id,
    ])->id;

    if (!$result)
      return response()->json(['success' => false, 'message' => __('response.error' . ($id ? '.update' : '.create'), ['item' => 'Product'])]);

    return response()->json(['success' => true, 'message' => __('response.success.' . ($id ? 'update' : 'create'), ['item' => 'Product']), 'value' => Hashids::encode($result)]);
  }

  public static function remove($id)
  {
    $result = self::find($id);
    if (!$result)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Product'])]);

    if ($result->variants()->count() > 0)
      return response()->json(['success' => false, 'message' => __('response.error.has_associated', ['item1' => 'Product', 'item2' => 'Variant(s)'])]);

    $result->delete();
    $result->deleted_by = auth('admin')->id();
    $result->save();

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Product'])]);
  }

  public static function toggleStatus($id)
  {
    $update = self::find($id);

    if (!$update)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Product'])]);

    $update->status = $update->status == 1 ? 0 : 1;
    $update->updated_by = auth('admin')->id();
    $update->save();

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Product Status']), 'newStatus' => $update->status]);
  }

  public static function multiDestroy($ids)
  {
    foreach ($ids as $id) {
      $result = self::remove($id)->getData(true);
      if ($result['success'] === false) {
        return response()->json($result);
      }
    }

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Product'])]);
  }

  public static function scopeSearch($query, $search)
  {
    return $query->where('name', 'like', '%' . $search . '%')
      ->orWhere('sku', 'like', '%' . $search . '%')
      ->orWhereHas('category', function ($query) use ($search) {
        $query->where('title', 'like', '%' . $search . '%');
      });
  }

  public function getDefaultImageAttribute()
  {
    return $this->variants()
      ->with(['images' => function ($query) {
        $query->where('is_default', true)->first();
      }])
      ->first()
      ?->images
      ?->first()
      ?->mediaGallery;
  }
}
