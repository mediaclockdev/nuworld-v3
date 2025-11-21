<?php

namespace App\Http\Controllers\Backend\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\System\SiteSettings\MailConfigRequest;
use App\Http\Requests\Backend\System\SiteSettings\SiteSettingsGenRequest;
use App\Http\Requests\ImageRequest;
use App\Services\Backend\System\SiteSettingsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class SiteSettingsController extends Controller
{
  public function __construct(protected SiteSettingsService $siteSettingsService) {}

  public function index(): View
  {
    $data = $this->siteSettingsService->getSettingsData();
    return view('backend.pages.system.site-settings', $data);
  }

  public function store(SiteSettingsGenRequest $request): JsonResponse
  {
    return $this->siteSettingsService->storeSettings($request->validated());
  }

  public function uploadLogo(ImageRequest $request): JsonResponse
  {
    return $this->siteSettingsService->uploadLogo($request->file('image'), 'site', 'logo');
  }

  public function uploadDashboardSmallLogo(ImageRequest $request): JsonResponse
  {
    return $this->siteSettingsService->uploadLogo($request->file('image'), 'site', 'logo_small', 'dashboard_small_logo');
  }

  public function updateMailConfig(MailConfigRequest $request): JsonResponse
  {
    return $this->siteSettingsService->updateMailConfig($request->except('_token'));
  }
}
