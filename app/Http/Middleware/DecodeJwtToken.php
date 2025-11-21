<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use App\Models\User;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use Symfony\Component\HttpFoundation\Response;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Vinkla\Hashids\Facades\Hashids;

class DecodeJwtToken
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    try {
      $payload = JWTAuth::parseToken()->getPayload();

      $tokenDeviceId = $payload->get('device_id');
      $incomingDeviceId = $request->header('X-Device-ID');

      // if (!$incomingDeviceId || $incomingDeviceId !== $tokenDeviceId) {
      //   return ApiResponse::error('Unauthorized Device', 403);
      // }

      $userId = Hashids::decode($payload->get('sub'))[0] ?? null;

      $user = User::find($userId);
      if (!$user)
        return ApiResponse::error('User not found', 404);

      Auth::setUser($user);
      return $next($request);
    } catch (TokenExpiredException $e) {
      return ApiResponse::error('Token expired', 401);
    } catch (TokenInvalidException $e) {
      return ApiResponse::error('Invalid token', 401);
    } catch (Exception $e) {
      return ApiResponse::error('Unauthorized', 401);
    }
  }
}
