<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseRequest;

class SubscribeEmailRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'email' => 'required|email:rfc,dns|unique:email_subscribes,email',

    ];
  }

  public function messages(): array
  {
    return [
      'email.required' => __('validation.required', ['attribute' => 'Email']),
      'email.email' => __('validation.invalid', ['attribute' => 'Email']),
      'email.unique' => __('validation.unique', ['attribute' => 'Email']),
    ];
  }
}
