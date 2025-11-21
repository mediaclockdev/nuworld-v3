<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;


class CategoryCheckoutCollectionBannerResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    $settings = (array) (is_string($this->settings) ? json_decode($this->settings, true) : $this->settings);

    $imageUrl = !empty($settings['image']) ? asset("public/storage/uploads/banners/{$settings['image']}") : null;
    $settings['image'] = $imageUrl;

    return [
      'id'          => Hashids::encode($this->id),
      'banner_type' => $this->position,
      'image'       => $imageUrl,
      'hyper_link'  => $settings['hyper_link'] ?? '',
      'alt_text'    => $settings['alt_text'] ?? '',
    ];
  }
}
