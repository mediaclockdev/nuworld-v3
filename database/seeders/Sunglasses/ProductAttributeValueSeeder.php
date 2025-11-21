<?php

namespace Database\Seeders\Sunglasses;

use Illuminate\Database\Seeder;
use App\Models\{ProductAttribute, ProductAttributeValue};

class ProductAttributeValueSeeder extends Seeder
{
  public function run()
  {
    // Create attributes
    $attributes = [
      'Lens Color'     => ['Black', 'Blue', 'Green', 'Golden'],
      'Frame Material' => ['Plastic', 'Metal', 'Wood'],
      'Lens Type'      => ['Polarized', 'Photochromic', 'Gradient'],
    ];

    $attributeMap = [];
    $attributeMapIds = [];

    foreach ($attributes as $attrName => $values) {
      $attr = ProductAttribute::create([
        'name' => $attrName
      ]);
      $attributeMapIds[$attrName] = $attr->id;
      foreach ($values as $val) {
        $value = ProductAttributeValue::create([
          'attribute_id' => $attr->id,
          'value' => $val,
          'value_details' => match ($val) {
            'Black' => '#000000',
            'Blue' => '#0000ff',
            'Green' => '#008000',
            'Golden' => '#ffd700',
            default => null,
          },
        ]);
        $attributeMap[$attrName][$val] = $value->id;
      }
    }
  }
}
