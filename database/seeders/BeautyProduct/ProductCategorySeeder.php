<?php

namespace Database\Seeders\BeautyProduct;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\{ProductCategory, MenuItem};

class ProductCategorySeeder extends Seeder
{
  protected $categories = [
    'Men' => [
      'Tops' => ['T-Shirts', 'Shirts'],
      'Bottoms' => ['Jeans', 'Trousers'],
    ],
    'Women' => [
      'Tops' => ['T-Shirts', 'Shirts'],
      'Bottoms' => ['Jeans', 'Trousers'],
    ],
    'Kids' => [
      'Boys Clothing' => ['Boy T-Shirts', 'Boy Jeans'],
      'Girls Clothing' => ['Girl Dresses', 'Girl Leggings'],
    ],
    'Footwear' => [
      'Men Footwear' => ['Sneakers', 'Formal Shoes'],
      'Women Footwear' => ['Heels', 'Flats'],
    ],
    'Accessories' => [
      'Bags' => ['Backpacks', 'Handbags'],
      'Jewelry' => ['Earrings', 'Necklaces'],
    ],
    'Outerwear' => [
      'Jackets' => ['Bomber Jackets', 'Denim Jackets'],
      'Coats' => ['Trench Coats', 'Wool Coats'],
    ],
    'Ethnic Wear' => [
      'Men Ethnic' => ['Kurtas', 'Sherwanis'],
      'Women Ethnic' => ['Sarees', 'Lehengas'],
    ],
    'Sportswear' => [
      'Active Tops' => ['Tank Tops', 'Running Tees'],
      'Active Bottoms' => ['Track Pants', 'Shorts'],
    ],
    'Loungewear' => [
      'Sleepwear' => ['Pajamas', 'Nightgowns'],
      'Casuals' => ['Lounge Pants', 'Casual Tees'],
    ],
    'Seasonal Wear' => [
      'Winter Essentials' => ['Sweaters', 'Thermals'],
      'Summer Essentials' => ['Shorts', 'Tank Tops'],
    ],
  ];

  public function run()
  {

    $parentCategories = [
      'Men',
      'Women',
      'Kids',
      'Footwear',
      'Accessories',
      'Outerwear',
      'Ethnic Wear',
      'Sportswear',
      'Loungewear',
      'Seasonal Wear'
    ];

    $childCategories = [
      'Men' => ['Tops', 'Bottoms'],
      'Women' => ['Tops', 'Bottoms'],
      'Kids' => ['Boys Clothing', 'Girls Clothing'],
      'Footwear' => ['Men Footwear', 'Women Footwear'],
      'Accessories' => ['Bags', 'Jewelry'],
      'Outerwear' => ['Jackets', 'Coats'],
      'Ethnic Wear' => ['Men Ethnic', 'Women Ethnic'],
      'Sportswear' => ['Active Tops', 'Active Bottoms'],
      'Loungewear' => ['Sleepwear', 'Casuals'],
      'Seasonal Wear' => ['Winter Essentials', 'Summer Essentials']
    ];

    $grandchildCategories = [
      'Tops' => ['T-Shirts', 'Shirts'],
      'Bottoms' => ['Jeans', 'Trousers'],
      'Boys Clothing' => ['Boy T-Shirts', 'Boy Jeans'],
      'Girls Clothing' => ['Girl Dresses', 'Girl Leggings'],
      'Men Footwear' => ['Sneakers', 'Formal Shoes'],
      'Women Footwear' => ['Heels', 'Flats'],
      'Bags' => ['Backpacks', 'Handbags'],
      'Jewelry' => ['Earrings', 'Necklaces'],
      'Jackets' => ['Bomber Jackets', 'Denim Jackets'],
      'Coats' => ['Trench Coats', 'Wool Coats'],
      'Men Ethnic' => ['Kurtas', 'Sherwanis'],
      'Women Ethnic' => ['Sarees', 'Lehengas'],
      'Active Tops' => ['Tank Tops', 'Running Tees'],
      'Active Bottoms' => ['Track Pants', 'Shorts'],
      'Sleepwear' => ['Pajamas', 'Nightgowns'],
      'Casuals' => ['Lounge Pants', 'Casual Tees'],
      'Winter Essentials' => ['Sweaters', 'Thermals'],
      'Summer Essentials' => ['Shorts', 'Tank Tops']
    ];
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

    $this->seedItems($this->categories, MenuItem::class, 1);
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
}
