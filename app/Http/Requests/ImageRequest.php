<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class ImageRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
    ];
  }

  public function messages(): array
  {
    return [
      'image.required' => __('validation.required', ['attribute' => 'Image']),
      'image.mimes' => __('validation.invalid', ['attribute' => 'Image Format']),
      'image.max' => __('validation.max', ['attribute' => 'Image', 'max' => '2 MB']),
    ];
  }
}
