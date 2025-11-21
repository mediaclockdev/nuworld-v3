<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\View;
use App\Models\AdminRole;
use App\Models\RolePermission;

class isAdmin
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (!auth()->guard('admin')->check()) {
      return redirect()->route('admin.login');
    } else if (auth()->guard('admin')->check() && !session()->get('verified')) {
      return redirect()->route('admin.login');
    }
    $user = user('admin');
    $role = AdminRole::getRole($user->id); // Share the user and role data with all views
    View::share('user', $user);
    View::share('role', $role);
    // Fetch Module and Submodules for this Role
    $menus = RolePermission::getMenus($role->id);
    View::share('menus', $menus);
    return $next($request);
  }
}
