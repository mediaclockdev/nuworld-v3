<?php

namespace App\Http\Requests\Frontend\Auth;

use App\Http\Requests\BaseRequest;

class SignupRequest extends BaseRequest
{
  public function rules(): array
  {
    return [
      'email' => [
        'nullable',
        'required_without:phone',
        'email:rfc,dns',
      ],

      'phone' => [
        'nullable',
        'required_without:email',
        'string',
        'regex:/^[0-9]{6,15}$/',
      ],

      'country_code' => [
        'nullable',
        'required_with:phone',
        'string',
        'regex:/^\+[1-9][0-9]{0,3}$/',
      ],
    ];
  }

  public function messages(): array
  {
    return [
      'email.required_without' => 'Email or mobile number is required.',
      'email.email' => __('validation.invalid', [
        'attribute' => 'Email Format',
      ]),

      'phone.required_without' => 'Email or mobile number is required.',
      'phone.regex' => __('validation.invalid', [
        'attribute' => 'Mobile Number',
      ]),

      'country_code.required_with' => 'Country code is required with mobile number.',
      'country_code.regex' => __('validation.invalid', [
        'attribute' => 'Country Code',
      ]),
    ];
  }
}
