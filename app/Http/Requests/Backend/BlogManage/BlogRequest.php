<?php

namespace App\Http\Requests\Backend\BlogManage;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends BaseRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'title'             => 'required|string|max:255|unique:blogs,title,' . $this->route('id') . ',id,deleted_at,NULL',
      'slug'              => 'required|string|max:255|unique:blogs,slug,' . $this->route('id') . ',id,deleted_at,NULL|lowercase',
      'post_id'           => 'required|exists:posts,id',
      'image'             => 'nullable|file|image|max:2048', // Max 2MB
      'short_description' => 'nullable|string|max:65535',
      'long_description'  => 'nullable|string|max:1000000', // Practical limit for LONGTEXT
      'published_at'      => 'nullable|date',
    ];
  }

  public function messages(): array
  {
    return [
      'title.required' => __('validation.required', ['attribute' => 'Blog Title']),
      'title.max' => __('validation.max', ['attribute' => 'Blog Title', 'max' => '255']),
      'title.unique' => __('validation.unique', ['attribute' => 'Blog Title']),

      'slug.required' => __('validation.required', ['attribute' => 'Slug']),
      'slug.max' => __('validation.max', ['attribute' => 'Slug', 'max' => '255']),
      'slug.unique' => __('validation.unique', ['attribute' => 'Slug']),
      'slug.lowercase' => __('validation.lowercase', ['attribute' => 'Slug']),

      'post_id.required' => __('validation.required', ['attribute' => 'Post']),
      'post_id.exists' => __('validation.exists', ['attribute' => 'Post']),

      'image.file' => __('validation.file', ['attribute' => 'Image']),
      'image.image' => __('validation.image', ['attribute' => 'Image']),
      'image.max' => __('validation.max', ['attribute' => 'Image', 'max' => '2MB']),

      // 'short_description.required' => __('validation.required', ['attribute' => 'Short Description']),
      'short_description.max' => __('validation.max', ['attribute' => 'Short Description', 'max' => '65535']),

      // 'long_description.required' => __('validation.required', ['attribute' => 'Long Description']),
      'long_description.max' => __('validation.max', ['attribute' => 'Long Description', 'max' => '1000000']),

      'published_at.date' => __('validation.date', ['attribute' => 'Published At']),
    ];
  }
}
