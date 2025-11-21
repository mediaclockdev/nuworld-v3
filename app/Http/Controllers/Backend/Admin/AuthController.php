<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Auth\LoginRequest;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\PasswordRequest;
use App\Models\Admin;
use App\Traits\UserAgentTrait;
use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use App\Models\AdminActivity;
use App\Models\SiteSetting;
use App\Services\UserAgentService;

class AuthController extends Controller
{

  use UserAgentTrait;

  public function __construct(protected UserAgentService $userAgentService) {}

  /**
   * Display the login page for the admin.
   * Check if the user is already logged in and the OTP is verified then redirect to the dashboard
   * @return View
   */
  public function login(): View|RedirectResponse
  {
    if (auth()->guard('admin')->check() && session()->has('verified')) {
      return redirect()->route('admin.dashboard');
    }

    return View('backend.pages.auth.login', ['pageTitle' => 'Admin Login']);
  }

  /**
   * Validates the login credentials and sends an OTP if two factor authentication is enabled
   *
   * @param LoginRequest $request
   * @return JsonResponse
   */
  public function validateLogin(LoginRequest $request): JsonResponse
  {

    //Check if 2FA Authentication
    $siteSettings      = SiteSetting::pluck('value', 'key')->toArray();
    $twoFactorAuth     = $siteSettings['two_factor_auth'] ?? null;
    $requiresTwoFactor = match ($twoFactorAuth) {
      '1' => true,                      // Force enable 2FA
      '2' => false,                     // Force disable 2FA
      default => config('app.two_factor_auth_admin'), // Use config
    };

    if (!Admin::accountActive($request->email))
      return response()->json(['success' => false, 'message' => __('response.auth.suspended')]);

    if (!$request->authenticate())
      return response()->json(['success' => false, 'message' => __('response.auth.failed')]);

    if (!$requiresTwoFactor) {
      $logID = AdminActivity::insertAgentActivity($this->getUserAgent($request));
      session(['verified' => true, 'logID' => $logID]);
      return response()->json(['success' => true, 'message' => trans('response.auth.success'), 'otp' => false]);
    }

    $response = app('SendEmailService')->OTP('admin', 'login')->getData();

    if ($response->success) {

      if ($request->remember === "true")
        session()->put('ecomm_email', $request->email);
      else
        session()->put('ecomm_email');

      return response()->json(['success' => true, 'message' => $response->message]);
    }

    return response()->json(['success' => false, 'message' => $response->message]);
  }

  /**
   * Displays the OTP verification page for the admin.
   * Redirects to the login page if the admin is not authenticated.
   * Redirects to the dashboard if the admin is authenticated and OTP is already verified.
   *
   * @return View|RedirectResponse
   */
  public function loginOtp(): View|RedirectResponse
  {
    if (!auth()->guard('admin')->check())
      return redirect()->route('admin.login');
    if (auth()->guard('admin')->check() && session()->has('verified'))
      return redirect()->route('admin.dashboard');

    return View('backend.pages.auth.login-otp', ['pageTitle' => 'Verify OTP']);
  }


  /**
   * Resends the OTP to the admin user via email.
   *
   * @param string $type The type of OTP to resend ('login' by default).
   * @return RedirectResponse A redirect response back to the previous page with a success or error message.
   */

  public function resendOtp(string $type = 'login'): RedirectResponse
  {
    $response = app('SendEmailService')->OTP('admin', $type)->getData();

    if ($response->success)
      return redirect()->back()->with('success', $response->message);

    return redirect()->back()->with('error', $response->message);
  }


  /**
   * Validates the OTP for the admin login.
   *
   * @param Request $request The HTTP request containing the OTP code.
   *
   * @return JsonResponse The JSON response containing the result of the validation.
   */
  public function validateLoginOtp(Request $request): JsonResponse
  {
    $otpCode = $request->input('email_otp');

    $isValidOtp = Verification::validateOTP('admin', 'email', $otpCode);

    if ($isValidOtp === -1)
      return response()->json(['success' => false, 'message' => __('response.otp.error.expired')]);

    if (!$isValidOtp)
      return response()->json(['success' => false, 'message' => __('response.otp.error.invalid')]);

    $logID = AdminActivity::insertAgentActivity($this->getUserAgent($request));

    session(['verified' => true, 'logID' => $logID]);

    return response()->json(['success' => true, 'message' => __('response.otp.success.verified')]);
  }


  /**
   * Log out the current admin user and invalidate the session.
   *
   * @param Request $request The request containing the session data.
   * @return RedirectResponse A redirect response to the login page.
   */
  public function logout(): RedirectResponse
  {
    $email = session('ecomm_email') ?? null;
    AdminActivity::setLogOut();
    session()->invalidate();
    session()->regenerateToken();
    session(['ecomm_email' => $email]);

    return redirect()->route('admin.login');
  }

  public function forgotPassword(): View
  {
    return View('backend.pages.auth.forgot-password', ['pageTitle' => 'Forgot Password']);
  }

  /**
   * Validates the email address and sends a password reset link if the email exists in the database.
   *
   * @param EmailRequest $request The request containing the email address.
   * @return JsonResponse A JSON response containing a success message.
   */
  public function validateForgotPassword(EmailRequest $request): JsonResponse
  {
    $id = Admin::getAdminIDByEmail($request->email);
    if (!$id)
      return response()->json(['success' => false, 'message' => __('response.auth.not_registered', ['item' => 'Email'])]);

    $token = Verification::genToken($id, 'admin');
    if (!$token)
      return response()->json(['success' => false, 'message' => __('response.auth.token.create')]);

    $deviceDetail = $this->getUserAgent($request);

    $params = [
      'resetUrl' => route('admin.reset_password') . '/' . $token,
      'date_time' => date('F d, Y, H:i:s'),
      'ip_address' => $deviceDetail['ip'],
      'browser' => $deviceDetail['browser'],
      'device' => $deviceDetail['device'],
      'os' => $deviceDetail['os'],
      'location' => $deviceDetail['location']
    ];
    // Send Password Reset link to email
    $response = app('SendEmailService')->ResetLink('admin', $request->email, $params)->getData();

    return response()->json(['success' => true, 'message' => __('response.auth.password.forgot')]);
  }

  /**
   * Shows the reset password form when a valid token is provided.
   * If the token is invalid or expired, returns an error message.
   * Otherwise, redirects to the login page.
   *
   * @param string|null $token The verification token.
   * @return View|RedirectResponse|string
   */
  public function resetPassword($token = null): View|RedirectResponse|string
  {
    if ($token) {
      $id = Verification::checkToken($token, 'admin');
      if ($id > 0) {
        session(['reset_password_user_id' => $id]);
        return View('backend.pages.auth.reset-password', ['pageTitle' => 'Reset Password']);
      }
      if ($id == -1)
        abort(403);

      abort(404);
    }

    return redirect()->route('admin.login');
  }

  /**
   * Handle change password request.
   *
   * @param PasswordRequest $request
   * @return JsonResponse
   */
  public function changePassword(PasswordRequest $request): JsonResponse
  {
    $id = session()->get('reset_password_user_id');
    if ($id)
      return Admin::changePassword($id, $request->password);
    return response()->json(['success' => false, 'message' => __('response.auth.password.error')]);
  }
}
