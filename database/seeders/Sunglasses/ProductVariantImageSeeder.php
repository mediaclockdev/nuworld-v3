<?php

namespace Database\Seeders\Sunglasses;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{Storage, File};

class ProductVariantImageSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $relativePath     = 'uploads/media/products/images';
    $disk             = Storage::disk('public');
    $destinationPath  = storage_path("app/public/{$relativePath}");
    $sourcePath       = public_path('SeederImages/Sunglasses/products');
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
