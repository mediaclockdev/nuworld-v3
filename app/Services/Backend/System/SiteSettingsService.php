<?php

namespace App\Services\Backend\System;

use App\Models\Country;
use App\Models\Currency;
use App\Models\MailSetting;
use App\Models\PaymentSettings;
use App\Models\Roles;
use App\Models\SiteSetting;
use App\Models\State;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\Storage;

class SiteSettingsService
{
  public function __construct(protected ImageUploadService $imageUploadService) {}

  public function getSettingsData(): array
  {
    return [
      'pageTitle' => 'Store Settings',
      'paymentSettings' => PaymentSettings::all(),
      'mail' => MailSetting::first(),
      'settings' => SiteSetting::all()->keyBy('key')->map(fn($item) => $item['value'])->toArray(),
      'country' => Country::all(),
      'stateList' => State::where('country_id', config('defaults.country_id'))->get(),
      'currencies' => Currency::orderBy('name', 'asc')->get(),
      'roles' => Roles::where('status', 1)->get()
    ];
  }

  public function storeSettings(array $validated)
  {
    return SiteSetting::store($validated);
  }

  public function uploadLogo($image, string $folderName, string $imageType, string $oldKey = 'site_logo')
  {
    $existing = SiteSetting::where('key', $oldKey)->value('value');

    if ($existing) {
      $path = "uploads/{$folderName}/{$imageType}/{$existing}";
      if (Storage::disk('public')->exists($path)) {
        Storage::disk('public')->delete($path);
      }
    }

    $upload = $this->imageUploadService->uploadImage($image, $folderName, $imageType, false);
    SiteSetting::updateLogo($upload['filename']);

    return response()->json([
      'success' => true,
      'message' => __('response.success.update', ['item' => 'Store Logo']),
      'path' => url('/public/storage/' . $upload['path'])
    ]);
  }

  public function updateMailConfig(array $params)
  {
    $success = MailSetting::updateMailConfig($params);
    return response()->json([
      'success' => $success,
      'message' => __(
        $success ? 'response.success.update' : 'response.error.update',
        ['item' => 'Mail Setting']
      )
    ]);
  }
}
