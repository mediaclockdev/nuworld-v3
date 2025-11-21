<?php

namespace Database\Seeders\BeautyProduct;

use Illuminate\Database\Seeder;
use App\Models\{ProductAttribute, ProductAttributeValue};

class ProductAttributeValueSeeder extends Seeder
{

  public function run()
  {

    // Create attributes
    $attributes = [
      'Color' => ['Black', 'Blue', 'Green'],
      'Fabric Type' => ['Cotton', 'Silk', 'Denim'],
      'Fit Type' => ['Slim Fit', 'Regular Fit', 'Oversized'],
      'Size' => ['S', 'M', 'L'],
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
            default => null,
          }
        ]);
        $attributeMap[$attrName][$val] = $value->id;
      }
    }
  }
}
