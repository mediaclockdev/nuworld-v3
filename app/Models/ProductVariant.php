<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;

class ProductVariant extends Model
{
  protected $fillable = [
    'product_id',
    'name',
    'regular_price',
    'sale_price',
    'sku',
    'sale_start_date',
    'sale_end_date',
    'attribute_details',
    'created_by',
    'status',
    'updated_by',
  ];

  protected $casts = [
    'attribute_details' => 'array',
  ];

  public function category()
  {
    return $this->hasOneThrough(ProductCategory::class, Product::class, 'id', 'id', 'product_id', 'category_id');
  }

  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  public function variantReview()
  {
    return $this->hasOne(ProductReview::class, 'variant_id', 'id')
      ->where('user_id', auth()->id());
  }

  public function variantAttributes()
  {
    return $this->hasMany(ProductVariantAttribute::class);
  }

  public function variantReviews()
  {
    return $this->hasMany(ProductReview::class, 'variant_id');
  }

  public function images()
  {
    return $this->hasMany(ProductVariantImages::class, 'product_variant_id')
      ->orderByDesc('is_default')
      ->orderBy('id');
  }

  public function orderDetails()
  {
    return $this->hasMany(OrderProduct::class, 'variant_id');
  }


  public function inventory()
  {
    return $this->hasOne(Inventory::class, 'product_variant_id');
  }

  public function galleries()
  {
    return $this->hasManyThrough(
      MediaGallery::class,
      ProductVariantImages::class,
      'product_variant_id',
      'id',
      'id',
      'media_gallery_id',
    )->orderBy('is_default', 'desc');
  }

  public function promotion()
  {
    return $this->hasMany(PromotionDetail::class, 'product_variant_id');
  }

  public static function store($variants, int $id = 0)
  {
    foreach ($variants as $variant) {
      $chkPrev = self::where('name', $variant['name'])->orWhere('sku', $variant['sku'])->where('id', '!=', $id)->first();
      if ($chkPrev)
        return response()->json(['success' => false, 'message' => 'Product Variant already exist']);

      $variantID = self::updateOrCreate(['id' => $id], [
        'product_id' => $variant['product_id'],
        'name' => $variant['name'],
        'regular_price' => $variant['regular_price'],
        'sku' => $variant['sku'],
        'created_by' => user('admin')->id
      ])->id;

      if (!$variantID)
        return response()->json(['success' => false, 'message' => __('response.error.create', ['item' => 'Product Variant (s)']),]);

      ProductVariantAttribute::store($variant['attributes'], $variantID);
    }
    return response()->json(['success' => true, 'message' => __('response.success.' . ($id ? 'update' : 'create'), ['item' => 'Product Variant(s)'])]);
  }

  public static function storeSingle($data, int $id = 0)
  {
    $saleDateRange = array_pad(explode(' - ', $data['sale_date'] ?? ''), 2, null);

    $variant = self::updateOrCreate(['id' => $id], [
      'regular_price' => $data['regular_price'],
      'sale_price' => $data['sale_price'],
      'sale_start_date' => $saleDateRange[0] ? formatDate($saleDateRange[0], 'Y-m-d') : null,
      'sale_end_date' => $saleDateRange[1] ? formatDate($saleDateRange[1], 'Y-m-d') : null,
      'updated_by' => user('admin')->id,
      'created_by' => $id ? self::find($id)->created_by : user('admin')->id,
    ]);

    return $variant;
  }

  public static function remove(int $id): JsonResponse
  {
    $variant = self::find($id);

    if (!$variant)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Variant'])]);

    $variant->variantAttributes()->delete();
    $variant->images()->delete();
    $variant->delete();

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Variant'])]);
  }

  public static function toggleStatus(int $id = 0): JsonResponse
  {
    $search = self::find($id);
    if (!$search)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Variant']),]);

    $search->status = $search->status ? 0 : 1;
    $search->updated_by = auth('admin')->id();
    $search->save();

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Variant Status']), 'newStatus' => $search->status]);
  }

  public static function scopeSearch($query, $search)
  {
    return $query->where('name', 'like', '%' . $search . '%');
  }

  public function colorOptions()
  {
    return $this->hasMany(ProductVariantAttribute::class)
      ->whereHas('attribute', function ($query) {
        $query->where('name', 'color'); // Dynamically target the 'color' attribute
      })
      ->with('attributeValue');
  }

  public function scopeWithCommonRelations($query)
  {
    return $query->with([
      'images',
      'variantAttributes.attribute',
      'variantAttributes.attributeValue',
      'product.category'
    ]);
  }
}
