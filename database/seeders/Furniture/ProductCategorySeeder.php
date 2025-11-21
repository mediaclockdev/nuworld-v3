<?php

namespace Database\Seeders\Furniture;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\{ProductCategory, MenuItem};
use App\Traits\Seeders\FurnitureCategoryData;
use Illuminate\Support\Facades\{Storage, File};

class ProductCategorySeeder extends Seeder
{
  public function __construct(protected FurnitureCategoryData $categoryData) {}
  public function run()
  {
    $levels               = $levels = $this->categoryData->getCategoryLevels();
    $parentCategories     = $levels['parentCategories'];
    $childCategories      = $levels['childCategories'];
    $grandchildCategories = $levels['grandchildCategories'];

    $count = 0;
    foreach ($parentCategories as $parentIndex => $parentTitle) {
      $parentCategory = ProductCategory::create([
        'title' => $parentTitle,
        'slug' => Str::slug($parentTitle),
        'sequence' => $parentIndex + 1,
        'category_image' => null
      ]);

      foreach ($childCategories[$parentTitle] as $c => $childTitle) {
        $childCategory = ProductCategory::create([
          'title' => $childTitle,
          'slug' => Str::slug($childTitle),
          'parent_id' => $parentCategory->id,
          'sequence' => $c + 1,
          'category_image' => null
        ]);

        foreach ($grandchildCategories[$childTitle] as $g => $grandTitle) {
          $count++;
          $grandchildCategory = ProductCategory::create([
            'title' => $grandTitle,
            'slug' => Str::slug($grandTitle),
            'parent_id' => $childCategory->id,
            'sequence' => $g + 1,
            'tax' => rand(0, 20),
            // 'category_image' => ($count == 1) ? '1.webp' : null
            'category_image' => rand(1, 6) . '.webp'
          ]);
        }
      }
    }

    $this->seedItems($this->categoryData->getNestedCategories(), MenuItem::class, 1);
    $this->uploadImages();
  }

  private function seedItems($categories, $model, $menuId = null, $parentId = null)
  {
    $sequence = 1;
    $categories = array_slice($categories, 0, 7);


    foreach ($categories as $key => $value) {
      $title = is_string($key) ? $key : $value;
      $children = is_array($value) ? $value : null;

      if (!is_string($title)) {
        throw new \Exception("Invalid title: expected string, got " . gettype($title));
      }

      $data = [
        'title' => $title,
        'slug' => Str::slug($title),
        'parent_id' => $parentId ?? 0,
        'sequence' => $sequence++,
      ];

      if ($menuId) {
        $data['menu_id'] = $menuId;
      }

      $item = $model::create($data);

      if ($children) {
        $this->seedItems($children, $model, $menuId, $item->id);
      }
    }
  }

  private function uploadImages()
  {
    // Define paths
    $relativePath     = 'uploads/categories';
    $disk             = Storage::disk('public');
    $destinationPath  = storage_path("app/public/{$relativePath}");
    $sourcePath       = public_path('SeederImages/Furniture/categories');

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
