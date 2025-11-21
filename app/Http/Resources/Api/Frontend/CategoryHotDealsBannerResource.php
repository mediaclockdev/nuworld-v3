<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;


class CategoryHotDealsBannerResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    $imageWithPath = asset('public/storage/uploads/banners/' . json_decode($this->settings)->image);
    $settings = is_string($this->settings) ? json_decode($this->settings, true) : (array) $this->settings;

    if (!empty($settings['image'])) {
      $settings['image'] = $imageWithPath;
    }

    return [
      'id'          => Hashids::encode($this->id),
      'banner_type' => $this->position,
      'settings'    => $settings,
    ];
  }
}
