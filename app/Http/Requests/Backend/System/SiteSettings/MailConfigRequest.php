<?php

namespace App\Http\Requests\Backend\System\SiteSettings;

use App\Http\Requests\BaseRequest;

class MailConfigRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'mail_mailer' => 'required',
      'mail_host' => 'required',
      'mail_port' => 'required|integer',
      'mail_username' => 'required',
      'mail_password' => 'required',
      'mail_encryption' => 'required',
      'mail_from_address' => 'required',
      'mail_from_name' => 'required',
    ];
  }

  public function messages(): array
  {
    return [
      'mail_mailer.required' => __('validation.required', ['attribute' => 'Mail Driver']),
      'mail_host.required' => __('validation.required', ['attribute' => 'Mail Host']),
      'mail_port.required' => __('validation.required', ['attribute' => 'Mail Port']),
      'mail_port.integer' => __('validation.numeric', ['attribute' => 'Mail Port']),
      'mail_username.required' => __('validation.required', ['attribute' => 'Mail Username']),
      'mail_password.required' => __('validation.required', ['attribute' => 'Mail Password']),
      'mail_encryption.required' => __('validation.required', ['attribute' => 'Mail Encryption']),
      'mail_from_address.required' => __('validation.required', ['attribute' => 'Mail From Address']),
      'mail_from_name.required' => __('validation.required', ['attribute' => 'Mail From Name']),
    ];
  }
}
