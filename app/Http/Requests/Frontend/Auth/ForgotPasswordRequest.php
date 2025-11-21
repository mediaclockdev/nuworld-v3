<?php

namespace App\Http\Requests\Frontend\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
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
			'email'     => 'required|regex:/^[\w\.\-]+@([\w\-]+\.)+[\w]{2,4}$/|exists:users,email',
		];
	}

	public function messages(): array
	{
		return [
			'email.required'        => 'Email is required.',
			'email.regex'           => 'Please enter a valid email address.',
			'email.exists'          => 'No account found with this email address.',
		];
	}
}
