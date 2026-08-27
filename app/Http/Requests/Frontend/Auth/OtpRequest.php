<?php

namespace App\Http\Requests\Frontend\Auth;

use App\Http\Requests\BaseRequest;

class OtpRequest extends BaseRequest
{
  public function rules(): array
  {
    return [
      'otp' => [
        'required',
        'digits:6',
      ],

      'fcm_token' => [
        'nullable',
        'string',
        'max:500',
      ],
    ];
  }

  public function messages(): array
  {
    return [
      'otp.required' => 'OTP is required.',

      'otp.digits' => 'OTP must be exactly 6 digits.',

      'fcm_token.string' => 'Invalid FCM token.',
    ];
  }
}
