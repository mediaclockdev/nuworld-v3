<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class ProductAttributeValueResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'id' => Hashids::encode($this->id),
      'value' => $this->value ?? null,
      'attribute_id' => Hashids::encode($this->attribute_id),
      'value_details' => $this->value_details ?? null,
      'sequence' => $this->sequence ?? null,
      'status' => (bool)$this->status,
    ];
  }
}
