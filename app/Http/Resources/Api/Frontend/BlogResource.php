<?php

namespace App\Http\Resources\Api\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class BlogResource extends JsonResource
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
      'post_id' => Hashids::encode($this->post_id),
      'image' => $this->image
        ? asset("public/storage/uploads/blogs/{$this->image}")
        : null,
      'short_description' => $this->short_description,
      'long_description' => $this->long_description,
      'status' => (bool)$this->status,
      'published_at' => convertDate($this->published_at),
      'created_by' => trim(userNameById('admin', $this->created_by)),
      'updated_by' => trim(userNameById('admin', $this->updated_by)),
      'created_at' => convertDate($this->created_at),
      'updated_at' => convertDate($this->updated_at),
      'post' => $this->post ? [
        'id' => Hashids::encode($this->post->id),
        'title' => $this->post->title,
        'slug' => $this->post->slug,
        'content' => $this->post->content,
        'status' => (bool)$this->post->status,
        'created_by' => trim(userNameById('admin', $this->post->created_by)),
        'updated_by' => trim(userNameById('admin', $this->post->updated_by)),
        'created_at' => convertDate($this->post->created_at),
        'updated_at' => convertDate($this->post->updated_at),
      ] : null,
    ];
  }
}
