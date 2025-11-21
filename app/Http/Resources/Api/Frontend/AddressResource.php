<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class AddressResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    if (!$this->resource) {
      return []; // Return empty array if no address
    }
    return [
      'id' => $this->id ?: null,
      'primary' => (bool) $this->primary,
      'name' => $this->name,
      'phone' => $this->phone,
      'address_line_1' => $this->address_1 ?? null,
      'address_line_2' => $this->address_2 ?? null,
      'landmark' => $this->landmark ?? null,
      'city_name' => $this->city ?? null,
      'pincode' => $this->pin ?? null,
      'state_id' => $this->state_id ? Hashids::encode($this->state_id) : null,
      'state_name' => $this->state?->name,
      'country_id' => $this->country_id ? Hashids::encode($this->country_id) : null,
      'country_name' => $this->state?->country?->name,
    ];
  }
}
