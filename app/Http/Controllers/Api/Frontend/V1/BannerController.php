<?php

namespace App\Http\Controllers\Api\Frontend\V1;

use App\Http\Controllers\Controller;
use App\Services\Frontend\BannerService;
use App\Helpers\ApiResponse;
use App\Http\Resources\Api\Frontend\BannerResource;


class BannerController extends Controller
{
  // get initial app banners
  public function appLaunchBanners()
  {
    $positions = ['app_journey_screen', 'app_splash_Logo', 'login_page_banner'];
    $allBanner = collect($positions)
      ->flatMap(fn($position) => BannerService::getBanner($position, false))
      ->values();

    return ApiResponse::success(BannerResource::collection($allBanner), __('response.success.fetch', ['item' => 'Initial banners']));
  }
}
