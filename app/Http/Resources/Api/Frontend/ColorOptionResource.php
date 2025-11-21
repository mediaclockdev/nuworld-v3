<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class ColorOptionResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'id' => Hashids::encode($this->variant_id),
      'sku' => $this->sku ?? '',
      'color' => $this->value ?? '',
      'image' => $this->image ?? null,
      'is_current' => $this->is_current ?? false
    ];
  }
}
