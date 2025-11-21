<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class ProductAttributeResource extends JsonResource
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
      'name' => $this->name ?? null,
      'sequence' => $this->sequence ?? null,
      'status' => (bool)$this->status,
      'values' => ProductAttributeValueResource::collection($this->values),
    ];
  }
}
