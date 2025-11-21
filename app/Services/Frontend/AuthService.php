<?php

namespace App\Services\Frontend;

use App\Mail\Frontend\{CustomResetPasswordMail, WelcomeUserMail};
use App\Models\{User, Verification};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Hash, Log, Mail, Password};
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class AuthService
{
  public function register(array $data): User
  {
    return DB::transaction(function () use ($data) {
      $user = User::create([
        'first_name'  => $data['first_name'],
        'last_name'    => $data['last_name'],
        'email'        => $data['email'],
        'phone'        => $data['phone'] ?? null,
        'password'     => Hash::make($data['password']),
      ]);

      // // Send welcome email
      // Mail::to($user->email)->send(new WelcomeUserMail($user));

      try {
        $view = 'emails.frontend.welcome';
        $data = [
          'user' => $user,
        ];
        $subject = 'Welcome to Mayuri Store!';
        $cc = [];
        $bcc = [];
        app('EmailService')->sendEmail($user->email, $subject, $view, $data, $cc, $bcc);
      } catch (\Exception $e) {
        // General email failure handling
        Log::error("Failed to send welcome email to {$user->email}: " . $e->getMessage());
      }
      return $user;
    });
  }

  public function registerlogin(array $data): User
  {
    return DB::transaction(function () use ($data) {
      $data['password'] = '12345678';
      $user = User::create([
        'first_name'  => $data['first_name'] ?? '',
        'email'        => $data['email'],
        'phone'      => !empty($data['phone']) ? $data['phone'] : null,
        'password'     => Hash::make($data['password']),
      ]);

      // // Send welcome email
      // Mail::to($user->email)->send(new WelcomeUserMail($user));

      try {
        $view = 'emails.frontend.welcome';
        $data = [
          'user' => $user,
        ];
        $subject = 'Welcome to Mayuri Store!';
        $cc = [];
        $bcc = [];
        app('EmailService')->sendEmail($user->email, $subject, $view, $data, $cc, $bcc);
      } catch (\Exception $e) {
        // General email failure handling
        Log::error("Failed to send welcome email to {$user->email}: " . $e->getMessage());
      }
      return $user;
    });
  }

  // public function getOtpUser()
  // {
  //   // You can retrieve the user from the session value
  //   $emailOrMobile = session('otp_email');

  //   if (!$emailOrMobile) {
  //     return null;
  //   }

  //   return User::where('email', $emailOrMobile)->orWhere('mobile', $emailOrMobile)->first();
  // }

  public function getOtpUser()
  {
    $emailOrMobile = trim(session('otp_email', ''));

    if (empty($emailOrMobile)) {
      return null;
    }

    return User::where('email', $emailOrMobile)
      ->orWhere('mobile', $emailOrMobile)
      ->first();
  }



  public function login(array $data): User
  {
    $user = User::where('email', $data['email'])->first();

    if (!$user) {
      throw ValidationException::withMessages([
        'email' => ['Email is not registered.'],
      ]);
    }

    if (!Hash::check($data['password'], $user->password)) {
      throw ValidationException::withMessages([
        'password' => ['Incorrect email or password. Please try again.'],
      ]);
    }

    return $user;
  }

  public function logout(Request $request): void
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
  }

  public function sendCustomPasswordResetLink($email)
  {
    $user = User::where('email', $email)->first();

    if (!$user) {
      return Password::INVALID_USER;
    }

    // Create token
    $token = Str::random(64);

    // Store token in verifications table
    Verification::updateOrInsert(
      ['user_id' => $user->id],
      [
        'token' => $token,
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
        'created_at' => now(),
        'updated_at' => now(),
      ]
    );

    // // Send custom mail
    // Mail::to($email)->send(new CustomResetPasswordMail($token, $user));

    $view = 'emails.frontend.custom-reset-password';
    $data = [
      'token' => $token,
      'user' => $user,
    ];
    $subject = 'Reset Your Password';
    $cc = [];
    $bcc = [];
    app('EmailService')->sendEmail($email, $subject, $view, $data, $cc, $bcc);

    return Password::RESET_LINK_SENT;
  }

  public function resetPassword(array $data)
  {
    $verification = Verification::where('token', $data['token'])
      ->latest()
      ->first();

    if (!$verification) {
      throw new \Exception('Invalid or expired token.');
    }

    // Check token expiry (1 hour)
    $updatedAt = Carbon::parse($verification->updated_at);
    if ($updatedAt->diffInMinutes(now()) > 60) {
      throw new \Exception('This reset link has expired. Please request a new one.');
    }

    $user = User::where('email', $data['email'])->first();

    if (!$user) {
      throw new \Exception('User not found.');
    }

    if (Hash::check($data['password'], $user->password)) {
      throw new \Exception('New password cannot be the same as current password.');
    }

    $user->password = Hash::make($data['password']);
    $user->save();

    Verification::where('id', $verification->id)->delete();

    return true;
  }
}
