<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Vinkla\Hashids\Facades\Hashids;

class ProductReviewResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */

  public function toArray($request)
  {

    return [
      'id' => $this->id,
      'user_name' => $this->user->full_name ?? '',
      'user_image' => userImageById('web', $this->user_id),
      'product_variant_id' => Hashids::encode($this->variant_id), //$this->variant_id ?? '',
      'rating' => $this->rating ?? '',
      'product_review' => $this->productreview ?? '',
      'status' => $this->status ?? '',
      'updated_at' => $this->updated_at
        ? $this->updated_at->format('d M Y')
        : '',
      'review_images' => ReviewImageResource::collection($this->reviewImages),
    ];
  }
}
