<?php

namespace App\Http\Middleware;

use Closure;

class CustomThrottleMiddleware extends \Illuminate\Routing\Middleware\ThrottleRequests
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  protected function buildResponse($key, $maxAttempts)
  {
    $response = response()->json([
      'success' => false,
      'message' => 'You have made 3 invalid attempts. Please try again after 1 minute.',
    ], 429);

    return $this->addHeaders(
      $response,
      $maxAttempts,
      $this->limiter->availableIn($key)
    );
  }
}
