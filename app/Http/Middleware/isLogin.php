<?php

namespace App\Http\Middleware;

use App\Models\AdminActivity;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isLogin
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    // Check if session has 'logID' and if the associated admin is logged in
    if (
      session()->has('logID') &&
      AdminActivity::where('id', session('logID'))->where('logged_in', 1)->exists()
    ) {
      return $next($request);
    }

    // Redirect to logout if conditions fail
    return redirect()->route('admin.logout');
  }
}
