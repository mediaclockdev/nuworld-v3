<?php

namespace App\Http\Requests\Backend\BlogManage;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends BaseRequest
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
      'title'    => 'required|string|max:255|unique:blogs,title,' . $this->route('id') . ',id,deleted_at,NULL',
      'slug'     => 'required|string|max:255|unique:blogs,slug,' . $this->route('id') . ',id,deleted_at,NULL|lowercase',
      'content'  => 'nullable|string|max:1000000',
    ];
  }

  public function messages(): array
  {
    return [
      'title.required' => __('validation.required', ['attribute' => 'Post Title']),
      'title.max' => __('validation.max', ['attribute' => 'Post Title', 'max' => '255']),
      'title.unique' => __('validation.unique', ['attribute' => 'Post Title']),

      'slug.required' => __('validation.required', ['attribute' => 'Slug']),
      'slug.max' => __('validation.max', ['attribute' => 'Slug', 'max' => '255']),
      'slug.unique' => __('validation.unique', ['attribute' => 'Slug']),
      'slug.lowercase' => __('validation.lowercase', ['attribute' => 'Slug']),

      // 'contenet.required' => __('validation.required', ['attribute' => 'Long Description']),
      'contenet.max' => __('validation.max', ['attribute' => 'Long Description', 'max' => '1000000']),
    ];
  }
}
