<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;


class BrandBannerResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    $settings = is_string($this->settings)
      ? json_decode($this->settings, true)
      : (array) $this->settings;

    $imageName = $settings['image'] ?? null;
    $imageUrl = $imageName ? asset("public/storage/uploads/banners/{$imageName}") : null;

    if ($imageUrl) {
      $settings['image'] = $imageUrl;
    }

    return [
      'id'          => Hashids::encode($this->id),
      'banner_type' => $this->position,
      'image'       => $imageUrl,
    ];
  }
}
