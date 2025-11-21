<?php

namespace Database\Seeders\Grocery;

use Illuminate\Database\Seeder;
use App\Models\{ProductAttribute, ProductAttributeValue};

class ProductAttributeValueSeeder extends Seeder
{
  public function run()
  {
    // Create attributes
    $attributes = [
      'Weight' => ['250g', '500g', '1kg']
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
        ]);
        $attributeMap[$attrName][$val] = $value->id;
      }
    }
  }
}
