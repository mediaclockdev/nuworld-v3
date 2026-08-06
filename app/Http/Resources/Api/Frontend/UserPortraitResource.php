<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class UserPortraitResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [

      'id' => $this->id,

      'gender' => $this->gender,

      'image' => $this->image
        ? asset('public/storage/' . $this->image)
        : null,

      // 'thumbnail' => $this->thumbnail
      //   ? asset('public/storage/' . $this->thumbnail)
      //   : null,

      'width' => $this->width,

      'height' => $this->height,

      'aspect_ratio' => $this->aspect_ratio,

      'status' => $this->status,
    ];
  }
}
