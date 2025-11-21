<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends BaseRequest
{
  public function rules(): array
  {
    return [
      'current_password' => 'required|string',
      'new_password'     => 'required|string|min:2',
      'confirm_password' => 'required|string|min:2|confirmed:new_password',
    ];
  }

  public function messages(): array
  {
    return [
      'confirm_password.confirmed' => __('validation.confirmed', ['attribute' => 'Confirm Password', 'target' => 'New Password']),
    ];
  }
}
