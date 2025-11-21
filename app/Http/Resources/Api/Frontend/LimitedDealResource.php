<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class LimitedDealResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    $saleInfo = findSalePrice($this->id);
    return [
      'id' => Hashids::encode($this->id),
      'title' => $this->name,
      'image' => asset($this->galleries[0]['file_name'] ?? null
        ? "storage/uploads/media/products/images/{$this->galleries[0]['file_name']}"
        : 'backend/assetss/images/products/product_thumb.jpg'),
      'price' => displayPrice($saleInfo['display_price']),
      'regular_price' => displayPrice($saleInfo['regular_price']),
      'discount' => $saleInfo['display_discount'],
      'promotion_id' => Hashids::encode($saleInfo['promotion_id']),
    ];
  }
}
