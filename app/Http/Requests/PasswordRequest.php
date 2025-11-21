<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class PasswordRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'password' => 'required',
    ];
  }

  public function messages(): array
  {
    return [
      'password.required' => __('validation.required', ['attribute' => 'Password']),
    ];
  }
}
