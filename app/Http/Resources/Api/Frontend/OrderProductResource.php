<?php

namespace App\Http\Resources\Api\Frontend;

use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class OrderProductResource extends JsonResource
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
      'order_id' => Hashids::encode($this->order_id),
      'variant_id' => Hashids::encode($this->variant_id),
      'name' => $this->variant->name ?? '',
      'product_name' => $this->variant->product->name ?? '',
      'sku' => $this->variant->sku ?? '',
      'image' => !empty($this->variant->galleries[0]['file_name'])
        ? asset('public/storage/uploads/media/products/images/' . $this->variant->galleries[0]['file_name'])
        : asset('public/backend/assetss/images/products/product_thumb.jpg'),
      'sell_price' => displayPrice($this->sell_price),
      'quantity' => $this->quantity ?? 0,
      'tax_amount' => displayPrice($this->tax_amount),
    ];
  }
}
