<?php

namespace App\Traits\Seeders;

use App\Traits\BaseCategoryDataTrait;

class SunglassesCategoryData
{
  use BaseCategoryDataTrait;

  public function getNestedCategories(): array
  {
    return [
      'Men' => [
        'Aviator' => ['Metal Aviators'],
        'Square' => ['Plastic Square'],
      ],
      'Women' => [
        'Cat Eye' => ['Vintage Cat Eye'],
        'Oversized' => ['Bold Oversized'],
      ],
      'Kids' => [
        'Fun Frames' => ['Cartoon Themed'],
        'Mini Shades' => ['Tiny Tints'],
      ],
      'Sports' => [
        'Cycling Glasses' => ['Wrap Around'],
        'Running Glasses' => ['Lightweight Frames'],
      ],
      'Fashion' => [
        'Trendy' => ['Pop Culture'],
        'Bold' => ['Statement Pieces'],
      ],
      'Prescription' => [
        'Reading' => ['Bifocal Frames'],
        'Custom Fit' => ['Tailored Specs'],
      ],
      'Polarized' => [
        'Anti-Glare' => ['Fishing Shades'],
        'HD Vision' => ['Driving Glasses'],
      ],
      'UV Protection' => [
        'UVA' => ['Shield Lenses'],
        'UVB' => ['Sun Protectors'],
      ],
      'Classic' => [
        'Timeless' => ['Old School'],
        'Retro' => ['Vintage Revival'],
      ],
      'Luxury' => [
        'Designer' => ['Luxury Labels'],
        'Premium' => ['Gold Rimmed'],
      ],
    ];
  }
}
