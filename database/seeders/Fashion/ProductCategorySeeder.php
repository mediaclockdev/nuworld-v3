<?php

namespace Database\Seeders\Fashion;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\ProductCategory;
use App\Models\MenuItem;
use App\Traits\Seeders\FashionCategoryData;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProductCategorySeeder extends Seeder
{
  public function __construct(protected FashionCategoryData $categoryData) {}

  public function run()
  {
    // Use nested structure directly
    $nested = $this->categoryData->getNestedCategories();

    $parentSequence = 1;
    $globalCount = 0;

    foreach ($nested as $parentTitle => $childrenMap) {

      // Create Parent Category
      $parentCategory = ProductCategory::create([
        'title' => $parentTitle,
        'slug' => Str::slug($parentTitle),
        'sequence' => $parentSequence++,
        'category_image' => null
      ]);

      $childSequence = 1;

      foreach ($childrenMap as $childTitle => $grandchildren) {

        // Create Child Category
        $childCategory = ProductCategory::create([
          'title' => $childTitle,
          'slug' => Str::slug($childTitle),
          'parent_id' => $parentCategory->id,
          'sequence' => $childSequence++,
          'category_image' => null
        ]);

        // Create Grandchild Categories
        foreach ($grandchildren as $g => $grandTitle) {
          $globalCount++;

          ProductCategory::create([
            'title' => $grandTitle,
            'slug' => Str::slug($grandTitle),
            'parent_id' => $childCategory->id,
            'sequence' => $g + 1,
            'tax' => rand(0, 20),
            'category_image' => rand(1, 6) . '.webp'
          ]);
        }
      }
    }

    // Create menu items using nested structure
    $this->seedItems($nested, MenuItem::class, 1);

    // Copy category images
    $this->uploadImages();
  }

  /**
   * Recursively seeds menu items
   */
  private function seedItems($categories, $model, $menuId = null, $parentId = null)
  {
    $sequence = 1;
    $categories = array_slice($categories, 0, 50); // prevent truncation to 7 if not desired

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

      // Recursively seed children
      if ($children) {
        $this->seedItems($children, $model, $menuId, $item->id);
      }
    }
  }

  /**
   * Copies category images
   */
  private function uploadImages()
  {
    $relativePath = 'uploads/categories';
    $disk = Storage::disk('public');
    $destinationPath = storage_path("app/public/{$relativePath}");
    $sourcePath = public_path('SeederImages/Fashion/categories');

    if (File::exists($destinationPath)) {
      File::deleteDirectory($destinationPath);
    }

    $disk->makeDirectory($relativePath);

    if (File::exists($sourcePath)) {
      foreach (File::files($sourcePath) as $file) {
        File::copy($file->getPathname(), "{$destinationPath}/{$file->getFilename()}");
      }
    } else {
      throw new \Exception("Source path does not exist: {$sourcePath}");
    }
  }
}
