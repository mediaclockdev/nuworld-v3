<?php

namespace App\Http\Resources\Api\Frontend;

use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class CartItemResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */

  public function toArray(Request $request): array
  {
    return [
      'id' => Hashids::encode($this->id),
      'product_variant_id' => Hashids::encode($this->product_variant_id),
      'quantity' => $this->quantity ?? 0,
      'is_saved_for_later' => $this->is_saved_for_later,
      'category' => $this->productVariant->category?->title ?? '',
      'name' => $this->productVariant->name ?? '',
      'sku' => $this->productVariant->sku ?? '',
      'price'           => displayPrice(findSalePrice($this->productVariant->id)['display_price']),
      'old_price'       => findSalePrice($this->productVariant->id)['regular_price_true'] ? null : displayPrice(findSalePrice($this->productVariant->id)['regular_price']),
      'is_discount'     => findSalePrice($this->productVariant->id)['regular_price_true'] ? false : true,
      'discount'        => findSalePrice($this->productVariant->id)['regular_price_true'] ? null : findSalePrice($this->productVariant->id)['display_discount'],
      'out_of_stock'    => ($this->productVariant->inventory?->quantity ?? 0) < 1,
      'image'           => !empty($this->productVariant->galleries[0]['file_name'])
        ? asset('public/storage/uploads/media/products/images/' . $this->productVariant->galleries[0]['file_name'])
        : asset('public/backend/assetss/images/products/product_thumb.jpg'),

    ];
  }
}
