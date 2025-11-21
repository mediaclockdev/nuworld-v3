<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class CategoryListResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray($request): array
  {
    return [
      'id'     => Hashids::encode($this->id),
      'title'  => $this->title,
      'slug'   => $this->slug,
      'image'  => $this->category_image ? asset('public/storage/uploads/categories/' . $this->category_image) : asset('public/backend/assetss/images/products/product_thumb.jpg'),
      'children' => CategoryListResource::collection($this->whenLoaded('children')),
    ];
  }
}
