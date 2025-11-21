<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Exception;

class ImageUploadService
{
  protected $manager;

  public function __construct()
  {
    $this->manager = new ImageManager(new Driver());
  }


  /**
   * Uploads an image, converts it to WebP format, and generates a thumbnail.
   *
   * @param UploadedFile $image
   * @param string $userType specifies directory
   * @param string $imageType specifies sub-directory
   * @param bool $genThumbnail to generate thumbnail
   * @return array
   * @throws Exception
   */
  public function uploadImage($image, string $directory = '', string $imageType = '', bool $genThumbnail = true): array
  {
    // Define directory structure
    $folder = "uploads/$directory/$imageType";
    $fileName = uniqid() . '.webp';

    // Ensure the directory exists
    if (!Storage::disk('public')->exists($folder)) {
      Storage::disk('public')->makeDirectory($folder);
    }

    try {
      // Store original image in WebP format
      $imageWebP = $this->manager->read($image->getRealPath())->toWebp(90);
      $imagePath = "$folder/$fileName";
      $thumbnailPath = "$folder/thumbnail/$fileName";
      Storage::disk('public')->put($imagePath, $imageWebP);

      if (!file_exists(Storage::disk('public')->path($imagePath)))
        return [
          'filename' => '',
          'path' => '',
        ];

      // Generate and store thumbnail
      if ($genThumbnail)
        $this->createThumbnail($image, $thumbnailPath);

      return [
        'filename' => $fileName,
        'path' => $imagePath,
      ];
    } catch (Exception $e) {
      throw new Exception('Failed to upload image: ' . $e->getMessage());
    }
  }

  /**
   * Generate a thumbnail in WebP format.
   *
   * @param \Illuminate\Http\UploadedFile $image
   * @param string $thumbnailPath
   * @return void
   * @throws Exception
   */
  protected function createThumbnail($image, string $thumbnailPath)
  {
    try {
      $thumbnail = $this->manager->read($image->getRealPath())
        ->resize(100, 100, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        })->toWebp(60);

      Storage::disk('public')->put($thumbnailPath, $thumbnail);
    } catch (Exception $e) {
      throw new Exception('Failed to create thumbnail: ' . $e->getMessage());
    }
  }
}
