<?php

namespace App\Http\Middleware;

use App\Models\AdminRole;
use App\Models\RolePermission;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissions
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $currentUserRoleId = AdminRole::getRole(user('admin')->id)->id;
    $currentRouteName = request()->route()->getName();

    if ($currentUserRoleId != 1) {
      $checkPermission = RolePermission::hasPermission($currentUserRoleId, $currentRouteName);
      if (!$checkPermission) {
        if ($request->ajax())
          return response()->json(['success' => false, 'message' => 'You do not have permission for this action.'], 403);
        else
          abort(Response::HTTP_FORBIDDEN);
      }
    }
    return $next($request);
  }
}
