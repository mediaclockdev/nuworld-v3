<?php

namespace App\Http\Resources\Api\Frontend;

use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class OrderHistoryResource extends JsonResource
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
      'order_id' => Hashids::encode($this->order_id),
      'order_status' => $this->status,
      'description' => $this->description ?? '',
      'order_status_label' => OrderStatus::labels()[$this->status] ?? 'Unknown',
      'scheduled_date' => \Carbon\Carbon::parse($this->scheduled_date)->format('D, j M Y'),
      'scheduled_time' => \Carbon\Carbon::parse($this->scheduled_time)->format('g:i A'),

    ];
  }
}
