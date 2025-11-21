<?php

namespace App\Http\Requests\Backend\Auth;

use App\Http\Requests\BaseRequest;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginRequest extends BaseRequest
{
  public function rules(): array
  {
    return [
      'email' => 'required|email|exists:admins',
      'password' => 'required',
    ];
  }

  public function messages(): array
  {
    return [
      'email.required' => __('validation.required', ['attribute' => 'Email']),
      'email.email' => __('validation.invalid', ['attribute' => 'Email Format']),
      'email.exists' => __('validation.not_registered', ['attribute' => 'Email']),
      'password.required' => __('validation.required', ['attribute' => 'Password']),
    ];
  }


  /**
   * Authenticate the user using the provided credentials.
   *
   * @return void
   * @throws \Illuminate\Validation\ValidationException
   */
  public function authenticate(): bool
  {
    $this->ensureIsNotRateLimited();

    $credentials = array_merge($this->only('email', 'password'));

    if (!Auth::guard('admin')->attempt($credentials, $this->boolean('remember'))) {
      RateLimiter::hit($this->throttleKey());
      return false;
    }
    RateLimiter::clear($this->throttleKey());
    return true;
  }


  /**
   * Ensure that the login attempts are not rate limited.
   *
   * @throws \Illuminate\Validation\ValidationException if too many attempts are made.
   */

  public function ensureIsNotRateLimited(): void
  {
    if (!RateLimiter::tooManyAttempts($this->throttleKey(), 3)) {
      return;
    }

    event(new Lockout($this));

    $seconds = RateLimiter::availableIn($this->throttleKey());

    throw ValidationException::withMessages([
      'auth_error' => trans('auth.throttle', [
        'seconds' => $seconds,
        'minutes' => ceil($seconds / 60),
      ]),
    ]);
  }

  /**
   * @param string $key
   * @return string
   */
  public function throttleKey(): string
  {
    return Str::lower($this->input('email')) . '|' . $this->ip();
  }
}
