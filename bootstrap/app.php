<?php

use App\Http\Middleware\CheckPermissions;
use App\Http\Middleware\CheckSwaggerAccess;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\isLogin;
use App\Http\Middleware\isSuperAdmin;
use App\Http\Middleware\PreventBackButton;
use App\Http\Middleware\DecodeHashid;
use App\Http\Middleware\DecodeJwtToken;
use App\Http\Middleware\CustomThrottleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    api: __DIR__ . '/../routes/api.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
      'isSuperAdmin' => isSuperAdmin::class,
      'isAdmin' => isAdmin::class,
      'isLogin' => isLogin::class,
      'preventBackButton' => PreventBackButton::class,
      'chkPermission' => CheckPermissions::class,
      'decodeHashid' => DecodeHashid::class,
      'decodeJwtToken' => DecodeJwtToken::class,
      'customThrottle' => CustomThrottleMiddleware::class,
      'checkSwaggerAccess' => CheckSwaggerAccess::class,
    ]);
  })
  ->withExceptions(function (Exceptions $exceptions) {
    //
  })->create();
