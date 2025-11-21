<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class BestSellingProductResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    $salePrice = findSalePrice($this->variant->id);
    $isDiscounted = !$salePrice['regular_price_true'];
    return [
      'id' => Hashids::encode($this->variant->id),
      'name' => $this->variant->name ?? '',
      'sku' => $this->variant->sku ?? '',
      'price' => displayPrice($salePrice['display_price']),
      'old_price' => $isDiscounted ? displayPrice($salePrice['regular_price']) : null,
      'is_discount' => $isDiscounted,
      'discount' => $isDiscounted ? $salePrice['display_discount'] : null,
      'out_of_stock' => ($this->variant->inventory?->quantity ?? 0) < 1,
      'category' => $this->variant->category?->title ?? '',
      'image' => !empty($this->variant->galleries[0]['file_name'])
        ? asset("public/storage/uploads/media/products/images/{$this->variant->galleries[0]['file_name']}")
        : asset('public/backend/assetss/images/products/product_thumb.jpg'),

    ];
  }
}
