<?php

namespace App\Services;

use App\Models\Verification;
use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Cache;


class LoginService
{
  public static function authenticationCheck($params): ?User
  {
    $email = $params->email;
    $user = User::where('email', $email)->first();
    $defaultPassword = config('defaults.default_password');

    if (!$user) {
      $user = User::create([
        'first_name' => 'Guest',
        'email' => $email,
        'phone' => null,
        'password' => bcrypt($defaultPassword),
      ]);
    } elseif ((int) $user->status === 2)
      return null;

    self::sendEmailOTP($user);
    return $user;
  }

  public static function verifyHash(string $hash): bool
  {
    $decoded = Hashids::decode(substr($hash, 16))[0] ?? '';
    return strlen($decoded) >= 11 && (time() - (int)substr($decoded, 0, 10) <= 300);
  }


  public static function otpVerification($params): array
  {
    if (!self::verifyHash($params->hash))
      return ['success' => false, 'message' => __('response.error.expired', ['item' => 'Hash'])];

    $decoded = Hashids::decode(substr($params->hash, 16))[0] ?? '';
    $uid = (int)substr($decoded, 10);

    $user = User::find($uid);

    $v = Verification::where([['user_id', $uid], ['email_otp', $params->otp]])->first();

    if (!$user || !$v) {
      return ['success' => false, 'message' => __('response.otp.error.invalid')];
    }

    $v->update(['email_otp' => null]);

    return ['success' => true, 'token' => JWTAuth::claims(['device_id' => $params->device_id])->fromUser($user)];
  }

  // public static function sendEmailOTP($user, $hash = null): void
  // {
  //   if ($hash) {
  //     self::verifyHash($hash);

  //     $decoded = Hashids::decode(substr($hash, 16))[0] ?? '';
  //     $uid = (int)substr($decoded, 10);

  //     $user = User::find($uid);
  //   }

  //   $otp = rand(100000, 999999);

  //   Verification::updateOrCreate(
  //     ['user_id' => $user->id],
  //     ['email_otp' => $otp]
  //   );

  //   $subject = 'Your OTP Code';
  //   $template = 'emails.otp';
  //   $params = [
  //     'name' => $user->name,
  //     'otp' => $otp,
  //   ];

  //   app('EmailService')->sendEmail($user->email, $subject, $template, $params, [], []);
  // }



  public static function sendEmailOTP($user, $hash = null): void
  {
    if ($hash) {
      self::verifyHash($hash);

      $decoded = Hashids::decode(substr($hash, 16))[0] ?? '';
      $uid = (int)substr($decoded, 10);

      $user = User::find($uid);
    }

    // 🔹 Per-user rate limit: max 3 OTPs per 60s
    $cacheKey = "otp_request_count_user_{$user->id}";
    $requestCount = Cache::get($cacheKey, 0);

    if ($requestCount >= 3) {
      // Too many requests → abort with JSON
      abort(response()->json([
        'success' => false,
        'message' => __('response.otp.error.too_many_requests', ['seconds' => 60]),
      ], 429));
    }

    // Increase request count, reset after 60 seconds
    Cache::put($cacheKey, $requestCount + 1, 60);

    // 🔹 Generate OTP
    $otp = rand(100000, 999999);

    // Save OTP in DB
    Verification::updateOrCreate(
      ['user_id' => $user->id],
      ['email_otp' => $otp]
    );

    // Send email
    $subject = 'Your OTP Code';
    $template = 'emails.otp';
    $params = [
      'name' => $user->name,
      'otp'  => $otp,
    ];

    app('EmailService')->sendEmail($user->email, $subject, $template, $params, [], []);
  }
}
