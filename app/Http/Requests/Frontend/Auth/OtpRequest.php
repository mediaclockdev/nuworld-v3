<?php

namespace App\Http\Requests\Frontend\Auth;

use App\Http\Requests\BaseRequest;

class OtpRequest extends BaseRequest
{

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'otp'       => 'required|digits:6',
    ];
  }

  public function messages(): array
  {
    return [
      'otp.required'        => __('validation.required', ['attribute' => 'OTP']),
      'otp.digits'          => 'Otp must be 6 digits.',
    ];
  }
}
