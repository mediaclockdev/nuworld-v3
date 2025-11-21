<?php

namespace Database\Seeders\BeautyProduct;

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
    Storage::disk('public')->makeDirectory('uploads/media/products/images');

    $sourcePath      = public_path('SeederImages/BeautyProduct/products');
    $destinationPath = storage_path('app/public/uploads/media/products/images');

    if (File::exists($destinationPath)) {
      File::cleanDirectory($destinationPath);
    }

    if (File::exists($sourcePath)) {
      $files = File::files($sourcePath);

      foreach ($files as $file) {
        File::copy($file->getPathname(), $destinationPath . '/' . $file->getFilename());
      }
    } else {
      throw new \Exception("Source path does not exist: {$sourcePath}");
    }
  }
}
