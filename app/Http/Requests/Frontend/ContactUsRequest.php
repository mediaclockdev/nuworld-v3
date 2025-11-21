<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Auth;


class ContactUsRequest extends BaseRequest
{
  public function rules(): array
  {
    return [
      'first_name' => 'required|string|max:255',
      'last_name' => 'nullable|string|max:255',
      'email' => 'required|email|max:255',
      'message' => 'required|string|min:10|max:1000',
      'captcha' => 'required|captcha'
    ];
  }

  public function messages(): array
  {
    return [
      'first_name.required' => __('validation.required', ['attribute' => 'First Name']),
      'first_name.max' => __('validation.maxlength', ['attribute' => 'First Name', 'max' => 255]),
      'last_name.max' => __('validation.maxlength', ['attribute' => 'Last Name', 'max' => 255]),
      'email.required' => __('validation.required', ['attribute' => 'Email']),
      'email.email' => __('validation.email', ['attribute' => 'Email']),
      'email.max' => __('validation.maxlength', ['attribute' => 'Email', 'max' => 255]),
      'message.required' => __('validation.required', ['attribute' => 'Message']),
      'message.min' => __('validation.minlength', ['attribute' => 'Message', 'min' => 10]),
      'message.max' => __('validation.maxlength', ['attribute' => 'Message', 'max' => 1000]),
      'captcha.required' => __('validation.required', ['attribute' => 'Captcha']),
      'captcha.captcha' => __('validation.invalid', ['attribute' => 'Captcha']),
    ];
  }
  protected function prepareForValidation()
  {
    // Sanitize the message field before validation
    $this->merge([
      'message' => strip_tags($this->input('message')),
    ]);
  }
}
