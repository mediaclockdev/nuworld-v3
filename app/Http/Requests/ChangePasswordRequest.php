<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class ChangePasswordRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'currentpassword' => 'required',
      'newpassword' => 'required',
    ];
  }

  public function messages(): array
  {
    return [
      'currentpassword.required' => __('validation.required', ['attribute' => 'Current Password']),
      'newpassword.required' => __('validation.required', ['attribute' => 'New Password']),
    ];
  }
}
