<?php

namespace App\Traits;

use App\Services\UserAgentService;

trait UserAgentTrait
{
  // private $userAgentService;


  /* public function __construct(UserAgentService $userAgentService)
  {
    // Initialize the user agent service
    $this->userAgentService = $userAgentService;
  } */

  public function getUserAgent(): array
  {
    $browser = $this->userAgentService->getUserBrowser() . ' v' . $this->userAgentService->getBrowserVersion();
    $ip = request()->header('X-Forwarded-For') ?: request()->ip();
    if ($ip != '::1' && $ip != '127.0.0.1')
      $location = $this->userAgentService->getLocation($ip);
    else
      $location = 'Localhost';
    $device = $this->userAgentService->getDeviceType();
    $os = $this->userAgentService->getUserOS();

    return [
      'browser' => $browser,
      'ip' => $ip,
      'location' => $location,
      'device' => $device,
      'os' => $os
    ];
  }
}
