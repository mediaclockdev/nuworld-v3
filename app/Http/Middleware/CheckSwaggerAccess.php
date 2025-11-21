<?php

namespace App\Http\Middleware;

use App\Models\AdminRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSwaggerAccess
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $user = user('admin');
    $allowedEmails = [
      'aritra@sundewsolutions.com',
      'rajib.sundew@yopmail.com',
      'sourav@sundewsolutions.com',
      'arpita.patra@sundewsolutions.com',
    ];

    if (!$user || !in_array($user->email, $allowedEmails))
      abort(403, 'Unauthorized access to Swagger docs.');
    return $next($request);
  }
}
