<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PortraitValidator
{
  public static function isValid(string $imagePath): bool
  {
    // Get image size locally
    $size = getimagesize($imagePath);
    if (!$size) {
      return false;
    }

    $imgW = $size[0];
    $imgH = $size[1];

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

    // ❌ Reject group photos
    if (count($data['faces']) !== 1) {
      return false;
    }

    $face = $data['faces'][0]['face_rectangle'];

    $faceArea = $face['width'] * $face['height'];
    $imgArea  = $imgW * $imgH;

    $ratio = $faceArea / $imgArea;

    // ❌ Face too small → not portrait
    if ($ratio < 0.20) {
      return false;
    }

    return true; // ✅ Valid portrait
  }
}
