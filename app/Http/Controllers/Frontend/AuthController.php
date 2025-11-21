<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Auth\{ForgotPasswordRequest, LoginRequest, OtpRequest, RegisterRequest, ResetPasswordRequest, SignupRequest};
use App\Models\{Cart, ProductVariant, User, UserActivity, Verification};
use App\Services\Frontend\AuthService;
use App\Traits\UserAgentTrait;
use Carbon\Carbon;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\Support\Facades\{Auth, DB, Log, Password};
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Vinkla\Hashids\Facades\Hashids;
use App\Services\Frontend\BannerService;
use App\Services\UserAgentService;
use App\Services\LoginService;


class AuthController extends Controller
{
  use UserAgentTrait;
  /**
   * Show the registration form.
   */

  public function __construct(protected BannerService $bannerService, protected UserAgentService $userAgentService) {}
  public function showRegisterForm(): View
  {
    $data = [];

    $data['title'] = 'Register';
    $data['showDisclaimer'] = false;
    return view('frontend.pages.user.auth.register', $data);
  }

  public function showLoginRegisterForm(): View
  {
    $data = [];
    $data['title'] = 'Login';
    $data['showDisclaimer'] = false;
    $data['login_page_banner'] = $this->bannerService->getBanner('login_page_banner', true);

    $previousUrl = url()->previous();
    $currentUrl  = request()->fullUrl();
    if ($previousUrl && $previousUrl !== $currentUrl) {
      session()->put('intended_url', $previousUrl);
    }
    return view('frontend.pages.user.auth.login-register', $data);
  }

  public function signin(SignupRequest $request)
  {
    $user = LoginService::authenticationCheck($request);
    if(empty($user)) {
      return response()->json(['success' => false, 'message' => __('response.auth.suspended')]);
    }
    $encodedId = Hashids::encode($user->id);
    session()->put('user_id', $encodedId);

    return response()->json([
      'message' => __('response.otp.success.sent.email'),
      'success' => true,
      'id'      => $encodedId,
    ]);
  }

  public function verifyotp(OtpRequest $request): JsonResponse
  {
    $userID = session('user_id');
    if (!$userID)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'User'])]);

    $userID = Hashids::decode($userID)[0];
    $verification = Verification::where('user_id', $userID)->where('email_otp', $request->otp)->first();

    if (!$verification)
      return response()->json(['success' => false, 'message' => __('response.otp.error.invalid')]);

    if ($verification->updated_at->addMinutes(config('app.otp_expiry', 5)) < now())
      return response()->json(['success' => false, 'message' => __('response.otp.error.expired')]);

    $verification->update(['email_otp' => null]);
    session()->forget('user_id');
    Auth::loginUsingId($userID);
    UserActivity::insertAgentActivity($this->getUserAgent($request));
    $this->syncGuestCartToUser();

    // Used to set location pincode after OTP verification
    if (session()->has('user_pincode')) {
      $address = Auth::user()->addresses()->where('primary', 1)->first();
      if ($address) {
        $address->update(['pin' => session('user_pincode')['Pincode'], 'city' => session('user_pincode')['Name']]);
      }
    }
    session([
      'user_pincode' => [
        'Name'    => Auth::user()->addresses()->where('primary', 1)->value('city'),
        'Pincode' => Auth::user()->addresses()->where('primary', 1)->value('pin'),
      ]
    ]);

    session(['user_addresses' => Auth::user()->addresses]);

    $redirectUrl = session()->pull('intended_url', route('home'));

    return response()->json([
      'success' => true,
      'message' => __('response.otp.success.verified'),
      'redirect' => $redirectUrl
    ]);
  }

  /**
   * Handle user registration.
   */
  public function register(RegisterRequest $request, AuthService $authService): JsonResponse
  {
    try {
      $user = $authService->register($request->validated());

      Auth::guard('web')->login($user);

      $this->syncGuestCartToUser();

      $redirectUrl = session()->get('url.intended', route('home'));

      return response()->json([
        'success' => true,
        'message' => 'Welcome! Your account has been created.',
        'redirect' => $redirectUrl,
      ]);
    } catch (\Exception $e) {
      // dd($e->getMessage());
      Log::error('Registration error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Your account was created, but we couldn\'t send the welcome email. Please check your email address or contact support.',
        'redirect' => session()->get('url.intended', route('home')),
      ], 200);
    }
  }



  /**
   * Show the login form.
   */
  public function showLoginForm(): View
  {
    $data = [];

    $data['title'] = 'Login';

    return view('frontend.pages.user.auth.login', $data);
  }

  /**
   * Handle user login.
   */
  public function login(LoginRequest $request, AuthService $authService): JsonResponse
  {
    try {
      $user = $authService->login($request->validated());

      $remember = $request->filled('remember');

      Auth::guard('web')->login($user, $remember);

      UserActivity::insertAgentActivity($this->getUserAgent($request));

      $this->syncGuestCartToUser();

      $redirectUrl = session()->get('url.intended', route('home'));

      return response()->json([
        'success' => true,
        'message' => 'Login successful.',
        'redirect' => $redirectUrl,
      ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'success' => false,
        'message' => 'The provided credentials are incorrect.',
        'errors'  => $e->errors(),
      ], 422);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Something went wrong. Please try again later.',
      ], 500);
    }
  }

  public function resendotp(Request $request): JsonResponse
  {
    $user = User::where('email', $request->email)->first();

    if (!$user) {
      return response()->json([
        'success' => false,
        'message' => 'User not found.',
      ], 404);
    }

    LoginService::sendEmailOTP($user);

    return response()->json([
      'success' => true,
      'message' => 'OTP resent successfully.',
    ]);
  }

  /**
   * Redirect to Google for social login.
   */
  public function redirectToGoogle(): RedirectResponse
  {
    return Socialite::driver('google')->redirect();
  }

  /**
   * Handle callback from Google after social login.
   */
  public function handleGoogleCallback(): RedirectResponse
  {
    try {
      $googleUser = Socialite::driver('google')->stateless()->user();

      if (!$googleUser->getEmail()) {
        throw new \Exception('Google account email not found');
      }

      $nameParts = explode(' ', $googleUser->getName());
      $firstName = $nameParts[0] ?? '';
      $middleName = count($nameParts) === 3 ? $nameParts[1] : '';
      $lastName = end($nameParts);

      // Check if user exists
      $user = User::where('email', $googleUser->getEmail())->first();


      if ($user && $user->status == 2) {
        return redirect()->route('signuplogin')->with('error', 'Account Suspended By Admin');
      }

      if (!$user) {
        // Create new user
        $user = User::create([
          'first_name'   => $firstName,
          'middle_name'  => $middleName,
          'last_name'    => $lastName,
          'email'      => $googleUser->getEmail(),
          'password'    => bcrypt(uniqid()),
        ]);

        // Mail::to($user->email)->send(new WelcomeUserMail($user));

        $view = 'emails.frontend.welcome';
        $data = [
          'user' => $user,
        ];
        $subject = 'Welcome to Mayuri Store!';
        $cc = [];
        $bcc = [];
        app('EmailService')->sendEmail($user->email, $subject, $view, $data, $cc, $bcc);
      }

      Auth::login($user);

      $this->syncGuestCartToUser();

      $redirectUrl = session()->get('url.intended', route('home'));

      return redirect()->to($redirectUrl);
    } catch (\Exception $e) {
      Log::error('Google login error: ' . $e->getMessage());
      return redirect()->route('signuplogin')->with('error', 'Failed to login with Google');
    }
  }

  /**
   * Sync guest cart from session to user's cart in the database.
   */
  public function syncGuestCartToUser(): bool
  {
    if (!Auth::check()) {
      return false; // No user logged in, nothing to sync
    }

    $guestCart = session()->pull('guest_cart', []);

    if (empty($guestCart)) {
      return true; // No guest cart to sync
    }

    $userId = Auth::id();

    try {
      DB::transaction(function () use ($guestCart, $userId) {
        foreach ($guestCart as $item) {
          // Validate required fields exist
          if (!isset($item['product_variant_id'])) {
            continue;
          }

          // Check if product variant exists
          if (!ProductVariant::where('id', $item['product_variant_id'])->exists()) {
            continue;
          }

          Cart::updateOrCreate(
            [
              'user_id' => $userId,
              'product_variant_id' => $item['product_variant_id'],
              'is_saved_for_later' => $item['is_saved_for_later'] ?? false,
            ],
            [
              'quantity' => $item['quantity'] ?? 1,
              'created_by' => $userId,
              'updated_by' => $userId,
            ]
          );
        }
      });

      return true;
    } catch (\Exception $e) {
      Log::error('Cart sync failed: ' . $e->getMessage(), [
        'user_id' => Auth::id(),
        'exception' => $e
      ]);
      return false;
    }
  }

  /**
   * Logout the user and redirect to home.
   */
  public function logout(Request $request, AuthService $authService): RedirectResponse
  {
    $authService->logout($request);

    return redirect()->route('home')->with('success', 'You have been logged out successfully.');
  }

  /**
   * Show forgot password form.
   */
  public function showForgotPasswordForm(): View
  {
    $data = [];

    $data['title'] = 'Forgot Password';

    return view('frontend.pages.user.auth.password-forgot', $data);
  }

  /**
   * Send password reset link to user's email.
   */
  public function sendResetLinkEmail(ForgotPasswordRequest $request, AuthService $authService): JsonResponse
  {
    try {
      $status = $authService->sendCustomPasswordResetLink($request->email);

      if ($status === Password::RESET_LINK_SENT) {
        return response()->json([
          'success' => true,
          'message' => 'Password reset link has been sent to your email.',
          'redirect' => route('signuplogin'),
        ]);
      }

      return response()->json([
        'success' => false,
        'message' => 'We couldn’t find an account with that email.',
      ], 422);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Something went wrong. Please try again later.',
      ], 500);
    }
  }

  /**
   * Show password reset form.
   */
  public function showResetForm($token): View
  {
    $data = [];

    $data['title'] = 'Reset Password';

    $data['token'] = $token;

    $verification = Verification::where('token', $data['token'])
      ->latest()
      ->first();

    if (!$verification) {
      return view('frontend.errors.404', ['message' => 'This link has expired or is no longer valid. Please request a new password reset link.']);
    }

    // Check token expiry (1 hour)
    $updatedAt = Carbon::parse($verification->updated_at);
    if ($updatedAt->diffInMinutes(now()) > 60) {
      return view('frontend.errors.404', ['message' => 'This link has expired or is no longer valid. Please request a new password reset link.']);
    }

    return view('frontend.pages.user.auth.password-reset', $data);
  }

  /**
   * Handle password reset.
   */
  public function resetPassword(ResetPasswordRequest $request, AuthService $authService): JsonResponse
  {
    try {
      $authService->resetPassword($request->validated());

      return response()->json([
        'success' => true,
        'message' => 'Your password has been reset successfully.',
        'redirect' => route('signuplogin'),
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage() ?: 'Something went wrong. Please try again.',
      ], 422);
    }
  }
}
