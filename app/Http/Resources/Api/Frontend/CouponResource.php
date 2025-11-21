<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class CouponResource extends JsonResource
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
      'code' => $this->code,
      'type' => $this->type,
      'amount' => $this->type === 'Percentage' ? $this->discount_amount . '%' : formatPrice($this->discount_amount),
      'min_order_value' => $this->min_order_value,
    ];
  }
}
