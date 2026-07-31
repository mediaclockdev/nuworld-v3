<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TryOnAvatarResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,

      'image' => [
        'path' => $this->image,
        'url' => $this->image
          ? asset('storage/' . $this->image)
          : null,
      ],

      'supported_regions' => $this->supported_regions ?? [],

      'is_default' => (bool) $this->is_default,

      'status' => (bool) $this->status,

      'created_at' => optional($this->created_at)->format('Y-m-d H:i:s'),

      'updated_at' => optional($this->updated_at)->format('Y-m-d H:i:s'),
    ];
  }
}
