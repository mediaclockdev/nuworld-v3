<?php

namespace App\Http\Requests\Frontend\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'first_name'  => "required|string|min:2|max:50|regex:/^[a-zA-Z.'\-\s]+$/",
      'last_name'    => "nullable|string|min:2|max:50|regex:/^[a-zA-Z.'\-\s]+$/",
      'email'       => 'required|email|regex:/^[\w\.\-]+@([\w\-]+\.)+[\w]{2,4}$/|unique:users,email',
      'phone'       => 'nullable|regex:/^[0-9+\-\s()]{7,20}$/|max:20',
      // 'password'  	=> 'required|string|min:8|max:20|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
      'password'    => 'required|string|min:6|max:20',
    ];
  }

  public function messages(): array
  {
    return [
      'first_name.required'  => 'Please enter your first name.',
      'first_name.min'    => 'First name must be at least 2 characters.',
      'first_name.max'    => 'First name can be up to 50 characters.',
      'first_name.regex'    => 'First name may only contain letters, spaces, dots, apostrophes, and hyphens.',

      'last_name.min'      => 'Last name must be at least 2 characters.',
      'last_name.max'      => 'Last name can be up to 50 characters.',
      'last_name.regex'    => 'Last name may only contain letters, spaces, dots, apostrophes, and hyphens.',

      'email.required'        => 'Email is required.',
      'email.regex'           => 'Please enter a valid email address.',
      'email.unique'          => 'This email is already registered.',

      'phone.regex'           => 'Please enter a valid phone number.',
      'phone.max'             => 'Phone number must not exceed 20 characters.',

      'password.required'     => 'Password is required.',
      'password.min'          => 'Password must be at least 6 characters.',
      'password.max'          => 'Password cannot exceed 20 characters.',
      // 'password.regex'        => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
    ];
  }
}
