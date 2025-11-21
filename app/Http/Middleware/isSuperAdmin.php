<?php

namespace App\Http\Middleware;

use App\Models\AdminRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isSuperAdmin
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (!AdminRole::exists() || user('admin')?->role_id === 1) {
      return $next($request);
    }

    abort(403);
  }
}
