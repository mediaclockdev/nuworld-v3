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
        'Tops' => ['Blouses', 'T-Shirts'],
        'Bottoms' => ['Jeans', 'Skirts'],
        'Footwear' => ['Heels', 'Flats'],
      ],
      'Men' => [
        'Tops' => ['Shirts', 'T-Shirts'],
        'Bottoms' => ['Jeans', 'Trousers'],
        'Footwear' => ['Sneakers', 'Formal Shoes'],
      ],
      'Kids' => [
        'Girls Clothing' => ['Dresses', 'Tops'],
        'Boys Clothing' => ['T-Shirts', 'Shorts'],
        'Kids Footwear' => ['School Shoes', 'Sandals'],
      ],
      // 'Accessories' => [
      //   'Bags' => ['Handbags', 'Backpacks'],
      //   'Jewelry' => ['Necklaces', 'Bracelets'],
      //   'Watches' => ['Digital Watches', 'Analog Watches'],
      // ],
      // 'Sportswear' => [
      //   'Active Tops' => ['Sports Bras', 'Performance Tees'],
      //   'Active Bottoms' => ['Leggings', 'Track Pants'],
      //   'Footwear' => ['Running Shoes', 'Training Shoes'],
      // ],
      // 'Ethnic Wear' => [
      //   'Women' => ['Sarees', 'Kurtis'],
      //   'Men' => ['Sherwanis', 'Kurtas'],
      //   'Kids' => ['Lehenga Sets', 'Kurta Sets'],
      // ],
      // 'Footwear' => [
      //   'Women' => ['Heels', 'Wedges'],
      //   'Men' => ['Loafers', 'Boots'],
      //   'Kids' => ['Sneakers', 'Sandals'],
      // ],
      // 'Winter Wear' => [
      //   'Jackets' => ['Puffer Jackets', 'Denim Jackets'],
      //   'Sweaters' => ['Cardigans', 'Pullovers'],
      //   'Hoodies' => ['Zip Hoodies', 'Graphic Hoodies'],
      // ],
      // 'Lingerie & Sleepwear' => [
      //   'Lingerie' => ['Bras', 'Panties'],
      //   'Sleepwear' => ['Pajamas', 'Nightgowns'],
      //   'Shapewear' => ['Bodysuits', 'Waist Shapers'],
      // ],
      // 'Bags & Travel' => [
      //   'Handbags' => ['Totes', 'Crossbody Bags'],
      //   'Travel Bags' => ['Duffel Bags', 'Trolley Bags'],
      //   'Wallets' => ['Leather Wallets', 'Card Holders'],
      // ],
    ];
  }
}
