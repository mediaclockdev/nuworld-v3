<?php

namespace Database\Seeders\Fashion;

use Illuminate\Database\Seeder;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;

class ProductAttributeValueSeeder extends Seeder
{
  public function run()
  {
    // Only attributes that appear in $categoryAttributeMap
    $attributes = [
      'Color' => [
        'Red',
        'Blue',
        'Green',
        'Black',

      ],

      'Size' => [
        'S',
        'M',
        'L',
        'XL',
        'XXL',
      ],

      // 'Material' => [
      //   'Cotton',
      //   'Polyester',
      //   'Leather',

      // ],

      'Bag Type' => [
        'Tote',
        'Crossbody',
      ],

      'Frame Color' => [
        'Red',
        'Blue',
        'Green',
        'Black',
      ],

      'Lens Type' => [
        'Polarized',
        'Clear',
      ],

      'Belt Size' => [
        'S (80cm)',
        'M (90cm)',
        'L (100cm)',
        'XL (110cm)',
      ],

      'Watch Type' => [
        'Analog',
        'Digital',
      ],

      'Band Material' => [
        'Leather',
        'Metal',
      ],

      'Dial Color' => [
        'Red',
        'Blue',
        'Green',
        'Black',
      ],
    ];

    foreach ($attributes as $attrName => $values) {
      // Create attribute if it doesn't exist
      $attribute = ProductAttribute::firstOrCreate(['name' => $attrName]);

      foreach ($values as $val) {
        // Create value if it doesn't exist
        ProductAttributeValue::firstOrCreate(
          [
            'attribute_id' => $attribute->id,
            'value'        => $val,
          ],
          [
            'value_details' => $this->valueDetails($attrName, $val),
          ]
        );
      }
    }
  }

  private function valueDetails(string $attrName, string $val): ?string
  {
    return match ($attrName) {

      // -------------------------------
      // COLOR (HEX CODES)
      // -------------------------------
      'Color' => match ($val) {
        'Red'   => '#FF0000',
        'Blue'  => '#0000FF',
        'Green' => '#008000',
        'Black' => '#000000',
        default => null,
      },

      // -------------------------------
      // SIZE (LABEL DESCRIPTIONS)
      // -------------------------------
      'Size' => match ($val) {
        'S'  => 'Small Size',
        'M'  => 'Medium Size',
        'L'  => 'Large Size',
        'XL' => 'Extra Large Size',
        'XXL' => 'Double Extra Large Size',
        default => null,
      },

      // -------------------------------
      // MATERIAL (FABRIC DESCRIPTIONS)
      // -------------------------------
      // 'Material' => match ($val) {
      //   'Cotton'    => 'Soft breathable natural fabric',
      //   'Polyester' => 'Durable synthetic fabric',
      //   'Leather'   => 'Premium genuine leather',
      //   default => null,
      // },

      // -------------------------------
      // BAG TYPE
      // -------------------------------
      'Bag Type' => match ($val) {
        'Tote'      => 'Large open-top bag with parallel handles',
        'Crossbody' => 'Long-strap bag worn across the body',
        default     => null,
      },

      // -------------------------------
      // FRAME COLOUR (SUNGLASSES)
      // -------------------------------
      'Frame Color' => match ($val) {
        'Red'   => '#FF0000',
        'Blue'  => '#0000FF',
        'Green' => '#008000',
        'Black' => '#000000',
        default => null,
      },

      // -------------------------------
      // LENS TYPE (SUNGLASSES)
      // -------------------------------
      'Lens Type' => match ($val) {
        'Polarized' => 'Reduces glare and reflections',
        'Clear'     => 'Transparent lenses',
        default     => null,
      },

      // -------------------------------
      // BELT SIZE
      // -------------------------------
      'Belt Size' => match ($val) {
        'S (80cm)'  => 'Fits waist 28–30 inches',
        'M (90cm)'  => 'Fits waist 32–34 inches',
        'L (100cm)' => 'Fits waist 36–38 inches',
        'XL (110cm)' => 'Fits waist 40–42 inches',
        default     => null,
      },

      // -------------------------------
      // WATCH TYPE
      // -------------------------------
      'Watch Type' => match ($val) {
        'Analog'      => 'Classic hour & minute hands',
        'Digital'     => 'LED/LCD numeric display',
        default       => null,
      },

      // -------------------------------
      // BAND MATERIAL
      // -------------------------------
      'Band Material' => match ($val) {
        'Leather' => 'Premium comfort leather strap',
        'Metal'   => 'Stainless steel band',
        default   => null,
      },

      // -------------------------------
      // DIAL COLOUR
      // -------------------------------
      'Dial Color' => match ($val) {
        'Red'   => '#FF0000',
        'Blue'  => '#0000FF',
        'Green' => '#008000',
        'Black' => '#000000',
        default => null,
      },

      default => null,
    };
  }
}
