<?php

namespace App\Http\Controllers\Api\Frontend\V1;

use App\Http\Controllers\Controller;
use App\Services\LoginService;
use App\Helpers\ApiResponse;
use App\Http\Requests\EmailRequest;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Frontend\Auth\SignupRequest;
use App\Http\Requests\Frontend\Auth\OtpRequest;
use App\Http\Requests\Frontend\Auth\RegisterRequest;
use App\Http\Requests\Frontend\HashRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Vinkla\Hashids\Facades\Hashids;

class AuthenticationController extends Controller
{
  /**
   * @OA\Post(
   *     path="/login",
   *     tags={"Auth"},
   *     summary="Authenticate user and send OTP",
   *     description="Authenticate user and send one-time password (OTP) to the user's email",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             required={"email"},
   *             @OA\Property(property="email", type="string", example="user@example.com"),
   *         )
   *     ),
   *     @OA\Response(response=200, description="OTP sent successfully"),
   *     @OA\Response(response=401, description="Authentication failed or user suspended")
   * )
   */
  public function login(SignupRequest $request): JsonResponse
  {
    $user = LoginService::authenticationCheck($request);
    if (empty($user))
      return ApiResponse::error(__('response.auth.suspended'));
    $hashCode = Str::random(16) . Hashids::encode(time() . $user->id);
    return ApiResponse::success(['hash' => $hashCode], __('response.otp.success.sent.email'));
  }





  /**
   * @OA\Post(
   *     path="/verify-otp",
   *     tags={"Auth"},
   *     summary="Verify OTP and return token",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             required={"hash", "otp", "device_id"},
   *             @OA\Property(property="hash", type="string"),
   *             @OA\Property(property="otp", type="string"),
   *             @OA\Property(property="device_id", type="string")
   *         )
   *     ),
   *     @OA\Response(response=200, description="OTP verified, JWT token returned"),
   *     @OA\Response(response=400, description="Invalid OTP or Hash/OTP expired")
   * )
   */
  public function verifyOtp(OtpRequest $request, EmailRequest $emailRequest, HashRequest $hashRequest): JsonResponse
  {
    $deviceId = $request->header('X-Device-ID') ?? (string) Str::uuid();

    $response = LoginService::otpVerification(
      $request->merge([
        'email' => $emailRequest->email,
        'hash' => $hashRequest->hash,
        'device_id' => $deviceId
      ])
    );
    return (isset($response['success']) && $response['success'] === false)
      ? ApiResponse::error($response['message'])
      : ApiResponse::success(['token' => $response['token']], __('response.otp.success.verified'));
  }

  /**
   * @OA\Post(
   *     path="/refresh-token",
   *     tags={"Auth"},
   *     summary="Refresh JWT token (valid for 1 week)",
   *     security={{"bearerAuth":{}}},
   *     @OA\Response(
   *         response=200,
   *         description="Token refreshed",
   *         @OA\JsonContent(
   *             @OA\Property(property="refresh_token", type="string"),
   *             @OA\Property(property="expires_in", type="integer", example=604800)
   *         )
   *     ),
   *     @OA\Response(response=401, description="Invalid token or token expired")
   * )
   */
  public function refreshToken(): JsonResponse
  {
    auth('api')->factory()->setTTL(10080);
    $token = JWTAuth::parseToken()->refresh();
    return ApiResponse::success([
      'refresh_token' => $token,
      'expires_in' => auth('api')->factory()->getTTL() * 60
    ], __('response.auth.token.refresh'));
  }

  /**
   * @OA\Post(
   *     path="/logout",
   *     tags={"Auth"},
   *     summary="Invalidate token and logout",
   *     security={{"bearerAuth":{}}},
   *     @OA\Response(response=200, description="Logged out successfully"),
   *     @OA\Response(response=401, description="Invalid or missing token")
   * )
   */
  public function logout(): JsonResponse
  {
    JWTAuth::invalidate(JWTAuth::getToken());
    return ApiResponse::success(__('response.auth.logout'));
  }

  /**
   * @OA\Post(
   *     path="/resend-otp",
   *     tags={"Auth"},
   *     summary="Resend OTP to the authenticated user",
   *     security={{"bearerAuth":{}}},
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             required={"hash"},
   *             @OA\Property(property="hash", type="string", example="abc123")
   *         )
   *     ),
   *     @OA\Response(response=200, description="OTP resent successfully"),
   *     @OA\Response(response=401, description="Unauthenticated")
   * )
   */
  public static function resendOtp(HashRequest $hashRequest): JsonResponse
  {
    $user = Auth::user();
    LoginService::sendEmailOTP($user, $hashRequest->hash);
    return ApiResponse::success(null, __('response.email.resent', ['item' => 'OTP']));
  }

  // For APP Login
  public function redirectToGoogle(): JsonResponse
  {
    $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
    return ApiResponse::success(['url' => $url]);
  }

  public function handleGoogleCallback(): JsonResponse
  {
    try {
      $googleUser = Socialite::driver('google')->stateless()->user();
      if (!$googleUser->getEmail()) {
        throw new \Exception('Google account email not found');
      }

      $nameParts = explode(' ', $googleUser->getName());
      $user = User::where('email', $googleUser->getEmail())->first();

      if ($user?->status == 2) {
        return ApiResponse::error('Account Suspended By Admin', 403);
      }

      $user ??= User::create([
        'first_name' => $nameParts[0] ?? '',
        'middle_name' => count($nameParts) === 3 ? $nameParts[1] : '',
        'last_name' => end($nameParts),
        'email' => $googleUser->getEmail(),
        'password' => bcrypt(uniqid()),
      ]);

      return ApiResponse::success([
        'access_token' => JWTAuth::fromUser($user),
        'token_type' => 'Bearer',
        'user' => $user,
        'message' => 'Google login successful',
      ]);
    } catch (\Exception $e) {
      return ApiResponse::error('Authentication failed: ' . $e->getMessage(), 401);
    }
  }
}
