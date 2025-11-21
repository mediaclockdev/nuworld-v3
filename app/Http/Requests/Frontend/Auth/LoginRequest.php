<?php

namespace App\Http\Requests\Frontend\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
      'email'     => 'required|regex:/^[\w\.\-]+@([\w\-]+\.)+[\w]{2,4}$/',
      // 'password'  => 'required|string|min:8|max:20|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
      'password'  => 'required|string|min:6|max:20',
      'remember'  => 'nullable|boolean',
    ];
  }

  public function messages(): array
  {
    return [
      'email.required'        => 'Email is required.',
      'email.regex'           => 'Please enter a valid email address.',

      'password.required'     => 'Password is required.',
      'password.min'          => 'Password must be at least 6 characters.',
      'password.max'          => 'Password cannot exceed 20 characters.',
      // 'password.regex'        => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
    ];
  }
}
