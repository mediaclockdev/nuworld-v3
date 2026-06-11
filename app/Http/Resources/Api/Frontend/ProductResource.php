<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class ProductResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */

  public function toArray($request)
  {
    $salePrice = findSalePrice($this->id);
    $isDiscounted = !$salePrice['regular_price_true'];
    $averageRating = $this->variantReviews()->avg('rating');
    $attributes = $this->attribute_details ?? [];

    return [
      'id' => Hashids::encode($this->id),
      'product_id' => Hashids::encode($this->product_id),
      'name' => $this->name ?? '',
      'product_name' => $this->product->name ?? '',
      'product_sku' => $this->sku ?? '',
      'attributes' => $attributes,
      'price' => displayPrice($salePrice['display_price']),
      'old_price' => $isDiscounted ? displayPrice($salePrice['regular_price']) : null,
      'is_discount' => $isDiscounted,
      'discount' => $isDiscounted ? $salePrice['display_discount'] : null,
      'out_of_stock' => ($this->inventory?->quantity ?? 0) < 1,
      'category' => $this->category?->title ?? '',
      'image' => !empty($this->galleries[0]['file_name'])
        ? asset("public/storage/uploads/media/products/images/{$this->galleries[0]['file_name']}")
        : asset('public/backend/assetss/images/products/product_thumb.jpg'),
      'hover_image' => !empty($this->galleries[1]['file_name'])
        ? asset("public/storage/uploads/media/products/images/{$this->galleries[1]['file_name']}")
        : asset('public/backend/assetss/images/products/product_thumb.jpg'),
      'is_in_cart' => isInCart($this->id, false),
      'is_in_wishlist' => isInCart($this->id, true),
      'avg_rating' => $averageRating !== null ? round((float) $averageRating, 1) : 0.0,
      'total_rating' => $this->variantReviews()->count(),
      // 'reviews' => $this->variantReviews() ?? [],
    ];
  }
}
