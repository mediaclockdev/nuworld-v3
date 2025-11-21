<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Jenssegers\Agent\Agent;

class UserAgentService
{
  protected $agent;

  public function __construct(Agent $agent)
  {
    $this->agent = $agent;
  }

  public function getUserBrowser()
  {
    return $this->agent->browser();
  }

  public function getUserDevice()
  {
    return $this->agent->device();
  }

  public function getUserOS()
  {
    return $this->agent->platform();
  }

  public function getBrowserVersion()
  {
    return $this->agent->version($this->getUserBrowser());
  }

  public function getDeviceType()
  {
    return ($this->agent->isMobile() ? 'Mobile' : ($this->agent->isDesktop() ? 'Desktop' : ($this->agent->isTablet() ? 'Tablet' : 'Unknown')));
  }

  public function getLocation($ipAddress = null)
  {
    $response = Http::get('http://ip-api.com/json/' . $ipAddress);
    $data = json_decode($response);
    return $data->city . ', ' . $data->regionName . ', ' . $data->country;
  }
}
