<?php

namespace Database\Seeders\Furniture;

use Illuminate\Database\Seeder;
use App\Models\{ProductAttribute, ProductAttributeValue};

class ProductAttributeValueSeeder extends Seeder
{
  public function run()
  {
    // Create attributes
    $attributes = [
      'Color' => ['Red', 'Blue', 'Green', 'Brown'],
      'Material' => ['Leather', 'Velvet', 'Wood', 'Plastic'],
    ];

    $attributeMap = [];
    $attributeMapIds = [];

    foreach ($attributes as $attrName => $values) {
      $attr = ProductAttribute::create([
        'name' => $attrName,
      ]);
      $attributeMapIds[$attrName] = $attr->id;
      foreach ($values as $val) {
        $value = ProductAttributeValue::create([
          'attribute_id' => $attr->id,
          'value' => $val,
          'value_details' => match ($val) {
            'Red' => '#ff0000',
            'Blue' => '#0000ff',
            'Green' => '#008000',
            'Brown' => '#964B00',
            default => null,
          },
        ]);
        $attributeMap[$attrName][$val] = $value->id;
      }
    }
  }
}
