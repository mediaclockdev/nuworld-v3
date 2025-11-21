<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class BaseProductResource extends JsonResource
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
      'name' => $this->name,
      'sku' => $this->sku,
      'image' => $this->product_image ? asset('public/storage/uploads/products/images/' . $this->product_image) : asset('public/backend/assetss/images/products/product_thumb.jpg'),
      
    ];
  }
}
