<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;


class BannerResource extends JsonResource
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
    $settings['title'] = $this->title ?? '';

    return [
      'id'          => Hashids::encode($this->id),
      'banner_type' => $this->position,
      'title' => $this->title ?? '',
      'sub_title' => $this->sub_title ?? '',
      'settings'    => $settings,
    ];
  }
}
