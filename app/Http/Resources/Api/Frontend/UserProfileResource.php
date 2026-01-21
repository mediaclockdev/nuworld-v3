<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class UserProfileResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'id' => Hashids::encode($this['user_data']->id),
      'first_name' => $this['user_data']->first_name,
      'last_name' => $this['user_data']->last_name,
      'email' => $this['user_data']->email,
      'phone' => $this['user_data']->phone,
      'gender' => $this['user_data']->gender,
      'dob' => $this['user_data']->dob,
      'default_profile_image' => $this['user_image'], // coming from service
      'default_avtar_profile_image' => $this['user_avtar_image'], // coming from service
    ];
  }
}
