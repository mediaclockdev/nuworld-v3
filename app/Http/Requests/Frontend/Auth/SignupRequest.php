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
                'prohibited_with:phone',
                'email:rfc,dns',
            ],

            'phone' => [
                'nullable',
                'required_without:email',
                'prohibited_with:email',
                'string',
                'regex:/^[0-9]{6,15}$/',
            ],

            'country_code' => [
                'nullable',
                'required_with:phone',
                'prohibited_with:email',
                'string',
                'regex:/^\+[1-9][0-9]{0,3}$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required_without' => __('validation.required', [
                'attribute' => 'Email or Mobile Number',
            ]),

            'email.prohibited_with' => 'Email cannot be provided with mobile number.',

            'email.email' => __('validation.invalid', [
                'attribute' => 'Email Format',
            ]),

            'phone.required_without' => __('validation.required', [
                'attribute' => 'Email or Mobile Number',
            ]),

            'phone.prohibited_with' => 'Mobile number cannot be provided with email.',

            'phone.regex' => __('validation.invalid', [
                'attribute' => 'Mobile Number',
            ]),

            'country_code.required_with' => 'Country code is required with mobile number.',

            'country_code.prohibited_with' => 'Country code cannot be provided with email.',

            'country_code.regex' => __('validation.invalid', [
                'attribute' => 'Country Code',
            ]),
        ];
    }
}
