<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseRequest;

class UploadPortraitRequest extends BaseRequest
{
  public function rules(): array
  {
    return [
      'gender' => 'required|in:male,female',
      'image' => 'required|image|mimes:png|max:10240',
    ];
  }

  public function messages(): array
  {
    return [
      'gender.required' => __('validation.required', [
        'attribute' => 'Gender',
      ]),

      'gender.in' => __('validation.in', [
        'attribute' => 'Gender',
      ]),

      'image.required' => __('validation.required', [
        'attribute' => 'Portrait Image',
      ]),

      'image.image' => __('validation.image', [
        'attribute' => 'Portrait Image',
      ]),

      'image.mimes' => __('validation.mimes', [
        'attribute' => 'Portrait Image',
        'values' => 'png',
      ]),

      'image.max' => __('validation.max.file', [
        'attribute' => 'Portrait Image',
        'max' => 10240,
      ]),
    ];
  }
}
