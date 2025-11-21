<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class Verification extends Model
{
  protected $table = 'verifications';

  protected $fillable = [
    'admin_id',
    'user_id',
    'phone_otp',
    'email_otp',
    'token',
    'ip_address',
    'user_agent',
  ];

  /**
   * Insert an OTP for a user or admin.
   *
   * @param string $userType The type of user ('user' or 'admin').
   * @param string $otpType The type of OTP ('email' or 'phone').
   * @param int $id The ID of the user or admin.
   * @return string The generated OTP.
   * @throws \Exception If the OTP insertion fails.
   */
  public static function insertOTP(string $userType = 'user', string $otpType = 'email', int $id = 0): string
  {
    $userColname = $userType === 'admin' ? 'admin_id' : 'user_id';
    $otp = rand(100000, 999999);

    $colname = $otpType === 'phone' ? 'phone_otp' : 'email_otp';

    try {
      self::updateOrCreate(
        [$userColname => $id],
        [$colname => $otp],
      );
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
    return $otp;
  }

  /**
   * @param string $userType The type of user ('user' or 'admin').
   * @param string $otpType The type of OTP ('email' or 'phone').
   * @param int $otp The OTP to validate.
   * @return int Whether the OTP is valid.
   * @throws \Exception If the OTP validation fails.
   */
  public static function validateOTP(string $userType, string $otpType, int $otp): int
  {
    $userColname = $userType === 'admin' ? 'admin_id' : 'user_id';
    $id = $userType === 'admin' ? user('admin')->id : user()->id;
    $colname = $otpType === 'phone' ? 'phone_otp' : 'email_otp';

    $otpDetail = self::where($userColname, $id)
      ->where($colname, $otp)
      ->whereBetween('updated_at', [
        Carbon::now()->subMinutes((int)config('app.otp_expiry')),
        Carbon::now()
      ])
      ->first([$colname]);

    //If OTP matches clear the OTP
    if ($otpDetail) {
      self::where($userColname, $id)->update([$colname => null]);
    }

    return $otpDetail ? 1 : ($otpDetail === null ? 0 : -1);
  }

  /**
   * Generate a token for the given user type and ID.
   *
   * @param int $id The ID of the user to generate the token for.
   * @param string $usertype The type of user ('user' or 'admin').
   * @return string The generated token.
   */
  public static function genToken(int $id = 0, string $usertype = 'user'): string
  {
    $token = Str::random(40);
    $colName = $usertype === 'admin' ? 'admin_id' : 'user_id';
    $saveToken = self::updateOrCreate([$colName => $id], ['token' => $token]);
    if (!$saveToken)
      return '';
    $linkString = Str::random(6) . $id . '$' . $token; // 6 random chars + id + $ + token for security
    return $linkString;
  }

  /**
   * Check if the given token is valid or not and return the ID associated with it.
   *
   * @param string $linkString The token string.
   * @param string $usertype The type of user ('user' or 'admin').
   * @return int The ID associated with the token if it is valid, otherwise 0.
   *             If the token is expired, return -1.
   */
  public static function checkToken(string $linkString = '', string $usertype = 'user'): int
  {
    $id = substr($linkString, 6, strpos($linkString, '$') - 6); // get id removing first 6 chars from the string
    $token = substr($linkString, strpos($linkString, '$') + 1); // get token string after  $
    $colName = $usertype === 'admin' ? 'admin_id' : 'user_id';

    $checkToken = self::where([[$colName, $id], ['token', $token]])->first();
    if (!$checkToken)
      return 0;  // invalid

    if (Carbon::parse($checkToken->updated_at)->addMinutes((int)config('app.reset_password_token_timeout'))->isPast())
      return -1; // expired
    return $id;
  }

  /**
   * Delete the token associated with the given user ID and type.
   *
   * @param int $id The ID of the user to delete the token for.
   * @param string $usertype The type of user ('user' or 'admin').
   * @return bool Whether the token was deleted successfully.
   */
  public static function deleteToken(int $id = 0, string $usertype = 'user'): bool
  {
    $colName = $usertype === 'admin' ? 'admin_id' : 'user_id';
    return self::where($colName, $id)->update(['token' => '']);
  }
}
