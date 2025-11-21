<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Vinkla\Hashids\Facades\Hashids;

class ProductVariantImages extends Model
{
  public $fillable = [
    'product_variant_id',
    'media_gallery_id',
    'is_default',
    'created_by'
  ];
  public function gallery()
  {
    return $this->belongsTo(MediaGallery::class, 'media_gallery_id', 'id');
  }

  public static function store(int $variantID = 0, int $imageID = 0, int $isDefault = 0)
  {
    if ($isDefault == 1) {
      self::where('product_variant_id', $variantID)->update(['is_default' => 0]);
    }

    self::create([
      'product_variant_id' => $variantID,
      'media_gallery_id' => $imageID,
      'is_default' => $isDefault,
      'created_by' => user('admin')->id
    ]);
  }

  public static function setDefaultImage($variantId = 0, $imageID): JsonResponse
  {
    self::where('product_variant_id', $variantId)->update(['is_default' => 0]);
    $actualImageId = Hashids::decode($imageID)[0] ?? 0;

    $update = self::where('id', $actualImageId)->update(['is_default' => 1]);
    if (!$update)
      return response()->json(['success' => false, 'message' => __('response.error.update', ['item' => 'Default Image'])]);

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Default Image'])]);
  }

  public static function remove(int $id = 0): JsonResponse
  {
    $image = self::find($id);
    if (!$image)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Image'])]);

    $image->delete();

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Image'])]);
  }
}
