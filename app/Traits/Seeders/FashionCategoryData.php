<?php

namespace App\Traits\Seeders;

use App\Traits\BaseCategoryDataTrait;

class FashionCategoryData
{
  use BaseCategoryDataTrait;

  public function getNestedCategories(): array
  {
    return [
      'Women' => [
        'Clothing' => [
          'Dresses',
          'Tops',
        ],
        'Outerwear' => [
          'Women Coats',
          'Women Jackets',
        ],

        'Accessories' => [
          'Bags',
          'Sunglasses',

        ],

      ],

      'Men' => [
        'Clothing' => [
          'Shirts',
          'T-Shirts',

        ],
        'Outerwear' => [
          'Men Jackets',
          'Men Coats',
        ],
        'Accessories' => [
          'Belts',
          'Watches',
        ],

      ],
    ];
  }
}
