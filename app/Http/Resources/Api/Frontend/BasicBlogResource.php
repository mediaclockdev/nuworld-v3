<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class BasicBlogResource extends JsonResource
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
        'title' => $this->title,
        'slug' => $this->slug,
        'image' => $this->image
            ? asset("public/storage/uploads/blogs/{$this->image}")
            : null,
        'short_description' => $this->short_description,
    ];
}

}
