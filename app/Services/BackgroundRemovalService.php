<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BackgroundRemovalService
{
  /**
   * Remove image background using Remove.bg
   *
   * @param string $originalImage
   * @return array
   */
  public function remove(string $originalImage): array
  {
    try {

      /*
            |--------------------------------------------------------------------------
            | Validate API Key
            |--------------------------------------------------------------------------
            */

      if (empty(config('services.removebg.api_key'))) {

        return [
          'success' => false,
          'message' => 'Remove.bg API key is not configured.',
        ];
      }

      /*
            |--------------------------------------------------------------------------
            | Validate Original Image
            |--------------------------------------------------------------------------
            */

      $absolutePath = Storage::disk('public')->path($originalImage);

      if (!file_exists($absolutePath)) {

        return [
          'success' => false,
          'message' => 'Original portrait image not found.',
        ];
      }

      /*
            |--------------------------------------------------------------------------
            | Send Image to Remove.bg
            |--------------------------------------------------------------------------
            */

      $response = Http::timeout(60)
        ->withHeaders([
          'X-Api-Key' => config('services.removebg.api_key'),
        ])
        ->attach(
          'image_file',
          fopen($absolutePath, 'r'),
          basename($absolutePath)
        )
        ->post(config('services.removebg.endpoint'), [

          'size'   => config('services.removebg.size', 'auto'),

          'format' => config('services.removebg.format', 'png'),

        ]);

      /*
            |--------------------------------------------------------------------------
            | Remove.bg Error
            |--------------------------------------------------------------------------
            */

      if (!$response->successful()) {

        $message = $response->json()['errors'][0]['title']
          ?? $response->body();

        return [
          'success' => false,
          'message' => $message,
        ];
      }

      /*
            |--------------------------------------------------------------------------
            | Create Processed Directory
            |--------------------------------------------------------------------------
            */

      $processedDirectory = 'tryon/portraits/processed';

      if (!Storage::disk('public')->exists($processedDirectory)) {

        Storage::disk('public')->makeDirectory($processedDirectory);
      }

      /*
            |--------------------------------------------------------------------------
            | Save Transparent PNG
            |--------------------------------------------------------------------------
            */

      $fileName = Str::uuid() . '.png';

      $processedImage = $processedDirectory . '/' . $fileName;

      $stored = Storage::disk('public')->put(
        $processedImage,
        $response->body()
      );

      if (!$stored) {

        return [
          'success' => false,
          'message' => 'Unable to save processed portrait.',
        ];
      }

      /*
            |--------------------------------------------------------------------------
            | Verify File Exists
            |--------------------------------------------------------------------------
            */

      if (!Storage::disk('public')->exists($processedImage)) {

        return [
          'success' => false,
          'message' => 'Processed portrait was not saved.',
        ];
      }

      /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

      return [

        'success' => true,

        'processed_image' => $processedImage,

        'processed_url' => asset('storage/' . $processedImage),

      ];
    } catch (\Throwable $e) {

      return [

        'success' => false,

        'message' => $e->getMessage(),

      ];
    }
  }
}
