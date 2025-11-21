<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class SearchListProductResource extends JsonResource
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
      'name' => $this->name ?? '',
      'product_sku' => $this->sku ?? '',
      'category' => $this->category?->title ?? '',
      'image' => !empty($this->galleries[0]['file_name'])
        ? asset("public/storage/uploads/media/products/images/{$this->galleries[0]['file_name']}")
        : asset('public/backend/assetss/images/products/product_thumb.jpg'),
    ];
  }
}
