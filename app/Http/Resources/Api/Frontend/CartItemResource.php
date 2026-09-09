<?php

namespace App\Http\Resources\Api\Frontend;

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
    $variant = $this->productVariant;

    // Get sale price ONCE
    $sale = findSalePrice($variant->id);

    $unitPrice = $sale['display_price'];
    $qty = $this->quantity ?? 0;

    $totalPrice = $unitPrice * $qty;

    $attributes = $variant->attribute_details ?? [];

    return [
      'id' => Hashids::encode($this->id),
      'product_variant_id' => Hashids::encode($this->product_variant_id),
      'quantity' => $qty,
      'is_saved_for_later' => $this->is_saved_for_later,

      'category' => $variant->category?->title ?? '',
      'product_name' => $variant->product?->name ?? '',
      'name' => $variant->name ?? '',
      'sku' => $variant->sku ?? '',
      'attributes' => $attributes,

      // ✅ Price info
      'unit_price' => displayPrice($unitPrice),
      'price' => displayPrice($totalPrice),

      'old_price' => $sale['regular_price_true']
        ? null
        : displayPrice($sale['regular_price']),

      'is_discount' => !$sale['regular_price_true'],

      'discount' => $sale['regular_price_true']
        ? null
        : $sale['display_discount'],

      // ✅ Stock
      'out_of_stock' => ($variant->inventory?->quantity ?? 0) < 1,

      // ✅ Image
      'image' => !empty($variant->galleries[0]['file_name'])
        ? asset('public/storage/uploads/media/products/images/' . $variant->galleries[0]['file_name'])
        : asset('public/backend/assetss/images/products/product_thumb.jpg'),
    ];
  }
}
