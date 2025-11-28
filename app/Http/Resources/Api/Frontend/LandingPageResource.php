<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LandingPageResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'site_logo' => $this['site_logo'] ?? null,
      'site_name' => $this['site_name'] ?? null,
      'cart_count' => $this['cart_count'] ?? 0,
      'wishlist_count' => $this['wishlist_count'] ?? 0,
      'trending_categories' => CategoryResource::collection($this['productCategories']) ?? [],
      'all_categories' => CategoryResource::collection($this['all_categories']) ?? [],
      'home_banner' => BannerResource::collection($this['home_banner']) ?? [],
      'home_inner_banner' => BannerResource::collection($this['home_inner_banner']) ?? [],
      'recommended_products' => ProductResource::collection($this['latest_products']) ?? [],
      // 'latest_products' => $this['footer_menu']
    ];
  }
}
