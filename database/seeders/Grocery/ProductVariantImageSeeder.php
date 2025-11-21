<?php

namespace Database\Seeders\Grocery;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{Storage, File};

class ProductVariantImageSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Define paths
    $relativePath     = 'uploads/media/products/images';
    $disk             = Storage::disk('public');
    $destinationPath  = storage_path("app/public/{$relativePath}");
    $sourcePath       = public_path('SeederImages/Grocery/products');

    if (File::exists($destinationPath)) {
      File::deleteDirectory($destinationPath);
    }
    $disk->makeDirectory($relativePath);
    // Copy files from source to destination
    if (File::exists($sourcePath)) {
      foreach (File::files($sourcePath) as $file) {
        File::copy($file->getPathname(), "{$destinationPath}/{$file->getFilename()}");
      }
    } else {
      throw new \Exception("Source path does not exist: {$sourcePath}");
    }
  }
}
