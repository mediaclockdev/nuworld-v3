<?php

namespace App\Http\Resources\Api\Frontend;

use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class OrderResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */

  public function toArray(Request $request): array
  {
    return  [
      'id'              => Hashids::encode($this->id),
      'order_number'            => $this->order_number ?? '',
      'transaction_id'           => $this->transaction_id ?? '',
      'payment_method'           => $this->payment_method === 1 ? 'Online Payment' : 'Cash On Delivery (COD)',
      'payment_status'           => $this->payment_status === 1 ? 'Success' : 'Failure',
      'coupon_id'           => $this->coupon_id,
      'coupon_discount'           => displayPrice($this->coupon_discount),
      'shipping_charge'           => displayPrice($this->shipping_charge),
      'order_total'           => displayPrice($this->order_total),
      'total_tax'           => displayPrice($this->total_tax),
      'net_total'           => displayPrice($this->net_total),
      'order_status' => $this->order_status,
      'order_status_label' => OrderStatus::labels()[$this->order_status] ?? 'Unknown',
      'shipping_address' => json_decode($this->shipping_address, true) ?? [],
      'billing_address' => json_decode($this->billing_address, true) ?? [],
      'order_date' => \Carbon\Carbon::parse($this->updated_at)->format('D, j M Y'),
      'order_products' => OrderProductResource::collection($this->whenLoaded('orderProducts')),
      'order_histories' => OrderHistoryResource::collection($this->whenLoaded('orderHistories')),
    ];
  }
}
