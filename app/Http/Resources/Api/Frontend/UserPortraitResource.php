<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPortraitResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   */
  public function toArray(Request $request): array
  {
    return [

      'portrait_id' => $this->id,

      'user_id' => $this->user_id,

      'gender' => $this->gender,

      'original_image' => $this->original_image
        ? asset('public/storage/' . $this->original_image)
        : null,

      'processed_image' => $this->processed_image
        ? asset('public/storage/' . $this->processed_image)
        : null,

      // 'thumbnail' => $this->thumbnail
      //   ? asset('storage/' . $this->thumbnail)
      //   : null,

      'width' => $this->width,

      'height' => $this->height,

      'aspect_ratio' => $this->aspect_ratio,

      'ready_for_tryon' => !empty($this->processed_image),

      'status' => (bool) $this->status,

      'created_at' => optional($this->created_at)->toDateTimeString(),

    ];
  }
}
