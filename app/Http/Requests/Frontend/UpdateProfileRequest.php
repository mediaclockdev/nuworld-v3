<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Auth;


class UpdateProfileRequest extends BaseRequest
{
  public function rules(): array
  {
    return [
      'first_name' => 'required|string|max:255',
      'middle_name' => 'nullable|string|max:255',
      'last_name'  => 'nullable|string|max:255',
      // 'email'      => 'required|email|unique:users,email,' . Auth::id(),
      'phone'      => 'required|unique:users,phone,' . Auth::id(),
      'gender'     => 'required|integer|in:1,2,3',
      'dob'        => 'nullable|date',
    ];
  }

  public function messages(): array
  {
    return [
      'first_name.required' => __('validation.required', ['attribute' => 'First Name']),
      'first_name.max'      => __('validation.maxlength', ['attribute' => 'First Name', 'max' => 255]),
      'last_name.max'       => __('validation.maxlength', ['attribute' => 'Last Name', 'max' => 255]),
      'middle_name.max'     => __('validation.maxlength', ['attribute' => 'Middle Name', 'max' => 255]),
      'email.required'      => __('validation.required', ['attribute' => 'Email']),
      'email.email'         => __('validation.email', ['attribute' => 'Email']),
      'email.unique'        => __('validation.unique', ['attribute' => 'Email']),
      'phone.required'      => __('validation.required', ['attribute' => 'Phone']),
      'phone.unique'        => __('validation.unique', ['attribute' => 'Phone']),
      'gender.required'     => __('validation.required', ['attribute' => 'Gender']),
      'gender.in'           => __('validation.in', ['attribute' => 'Gender', 'values' => '1,2,3']),
    ];
  }
}
 