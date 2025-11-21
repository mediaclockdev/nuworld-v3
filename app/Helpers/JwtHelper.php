<?php


use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Vinkla\Hashids\Facades\Hashids;


if (!function_exists('ifApiTokenExists')) {
  function ifApiTokenExists(): void
  {
    try {
      if ($token = JWTAuth::getToken()) {
        $userId = Hashids::decode(JWTAuth::setToken($token)->getPayload()->get('sub'))[0] ?? null;
        $user = User::find($userId);
        if ($user) {
          Auth::setUser($user);
        }
      }
    } catch (\Exception $e) {
      // Silently fail: allow guest access
    }
  }
}
