<?php

namespace Database\Seeders\Fashion;

use Illuminate\Database\Seeder;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;

class ProductAttributeValueSeeder extends Seeder
{
    public function run()
    {
        // Fashion-Wireframes dataset
        $attributes = [
            'Color' => ['Red', 'Blue', 'Green', 'Black'],
            'Material' => ['Cotton', 'Polyester', 'Leather', 'Silk'],
            'Size' => ['S', 'M', 'L', 'XL'],
        ];

        foreach ($attributes as $attrName => $values) {

            // Create attribute
            $attribute = ProductAttribute::create([
                'name' => $attrName,
            ]);

            // Create values under this attribute
            foreach ($values as $val) {

                ProductAttributeValue::create([
                    'attribute_id' => $attribute->id,
                    'value' => $val,
                    'value_details' => match ($val) {

                        // Colors
                        'Red'   => '#FF0000',
                        'Blue'  => '#0000FF',
                        'Green' => '#008000',
                        'Black' => '#000000',


                        // Material notes
                        'Cotton'     => 'Soft & breathable',
                        'Polyester'  => 'Durable fabric',
                        'Leather'    => 'Premium finish',
                        'Silk'       => 'Luxury texture',

                        // Size labels
                        'S'  => 'Small',
                        'M'  => 'Medium',
                        'L'  => 'Large',
                        'XL' => 'Extra Large',

                        default => null,
                    },
                ]);
            }
        }
    }
}
