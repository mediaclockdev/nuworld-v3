<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseRequest;

class CaptchaRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'captcha' => 'nullable|captcha',
    ];
  }

  public function messages(): array
  {
    return [
      'captcha.required' => __('validation.required', ['attribute' => 'Captcha']),
      'captcha.captcha' => __('validation.invalid', ['attribute' => 'Captcha']),
    ];
  }
}
