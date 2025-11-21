<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class VariantDetails extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    $encodedImages = [];

    foreach ($this->images as $image) {
      $encodedImages[] = [
        'id' => Hashids::encode($image->id),
        'file_type' => $image->gallery->file_type,
        'file_name' => $image->gallery->file_name,
        'is_default' => $image->is_default
      ];
    }

    return [
      'variant_name' => $this->name,
      'variant_sku' => $this->sku,
      'variant_regular_price' => $this->regular_price ?? 0,
      'variant_sale_price' => $this->sale_price ?? 0,
      'variant_sale_start_date' => $this->sale_start_date ?? null,
      'variant_sale_end_date' => $this->sale_end_date ?? null,
      'variant_stock' => $this->inventory->quantity ?? 0,
      'variant_threshold' => $this->inventory->threshold ?? 0,
      'variant_max_quantity' => $this->inventory->max_selling_quantity ?? 0,
      'variant_images' => $encodedImages
    ];
  }
}
