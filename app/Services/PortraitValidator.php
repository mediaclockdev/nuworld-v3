<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PortraitValidator
{
  public static function isValid(string $imagePath): bool
  {
    $response = Http::asMultipart()
      ->timeout(60)
      ->post('https://api-us.faceplusplus.com/facepp/v3/detect', [
        'api_key' => config('services.facepp.key'),
        'api_secret' => config('services.facepp.secret'),
        'image_file' => fopen($imagePath, 'r'),
      ]);

    if (!$response->ok()) {
      return false;
    }

    $data = $response->json();

    // ❌ No face
    if (empty($data['faces'])) {
      return false;
    }

    // ❌ Reject group photos (more than 1 face)
    if (count($data['faces']) !== 1) {
      return false;
    }

    // ✅ Exactly one human face exists
    return true;
  }
}
