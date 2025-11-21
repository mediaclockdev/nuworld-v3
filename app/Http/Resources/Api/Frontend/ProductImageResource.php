<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class ProductImageResource extends JsonResource
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
      'is_default' => (bool) $this->is_default,
      'image' => $this->gallery ? asset('public/storage/uploads/media/products/images/' . $this->gallery->file_name) : asset('public/backend/assetss/images/products/product_thumb.jpg'),
    ];
  }
}
