<?php

namespace Database\Seeders\BeautyProduct;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{Product, ProductCategory, ProductVariant, ProductVariantImages, ProductVariantAttribute, Inventory, MediaGallery, ProductAttribute, ProductAttributeValue};
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
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

    foreach (range(1, 10) as $i) {
      MediaGallery::create([
        'file_name' => 'b-' . $i . '.webp',
        'file_type' => 'image/webp',
      ]);
    }

    $attributes = [
      'Color' => ['Black', 'Blue', 'Green'],
      'Fabric Type' => ['Cotton', 'Silk', 'Denim'],
      'Fit Type' => ['Slim Fit', 'Regular Fit', 'Oversized'],
      'Size' => ['S', 'M', 'L'],
    ];

    $attributeMap = [];
    $attributeMapIds = [];

    foreach ($attributes as $attrName => $values) {
      $attr = ProductAttribute::where('name', $attrName)->first();
      if (!$attr) continue;

      $attributeMapIds[$attrName] = $attr->id;

      foreach ($values as $val) {
        $value = ProductAttributeValue::where('value', $val)->where('attribute_id', $attr->id)->first();
        if (!$value) continue;
        $attributeMap[$attrName][$val] = $value->id;
      }
    }


    foreach ($parentCategories as $parentIndex => $parentTitle) {
      $parent = ProductCategory::where('title', $parentTitle)->first();
      if (!$parent) continue;


      foreach ($childCategories[$parentTitle] as $c => $childTitle) {
        $child = ProductCategory::where('title', $childTitle)->where('parent_id', $parent->id)->first();
        if (!$child) continue;


        foreach ($grandchildCategories[$childTitle] as $g => $grandTitle) {
          $grandchild = ProductCategory::where('title', $grandTitle)->where('parent_id', $child->id)->first();
          if (!$grandchild) continue;

          for ($p = 1; $p <= 2; $p++) {
            $productName = "$grandTitle Fashion $p";
            $product = Product::create([
              'name' => $productName,
              'sku' => strtoupper(Str::slug($productName . '-' . substr(uniqid(), -4))),
              'category_id' => $grandchild->id,
              'type' => 'variable',
              'status' => 1,
            ]);

            foreach ($attributeMap['Color'] as $color => $colorId) {
              foreach ($attributeMap['Fabric Type'] as $fabricType => $fabricTypeId) {
                foreach ($attributeMap['Fit Type'] as $fitType => $fitTypeId) {
                  foreach ($attributeMap['Size'] as $size => $sizeId) {
                    $variantName = "$productName $color $fabricType $fitType $size";

                    $variant = ProductVariant::create([
                      'product_id' => $product->id,
                      'name' => $variantName,
                      'sku' => strtoupper(Str::slug($variantName . '-' . substr(uniqid(), -4))),
                      'regular_price' => $regularPrice = rand(150, 300),
                      'sale_price' => rand(100, $regularPrice - 1),
                    ]);

                    ProductVariantImages::create([
                      'product_variant_id' => $variant->id,
                      'media_gallery_id' => DB::table('media_galleries')->inRandomOrder()->first()->id,
                      'is_default' => 1
                    ]);


                    ProductVariantAttribute::create([
                      'product_variant_id' => $variant->id,
                      'attribute_id' => $attributeMapIds['Color'],
                      'attribute_value_id' => $colorId,
                    ]);

                    ProductVariantAttribute::create([
                      'product_variant_id' => $variant->id,
                      'attribute_id' => $attributeMapIds['Fabric Type'],
                      'attribute_value_id' => $fabricTypeId,
                    ]);

                    ProductVariantAttribute::create([
                      'product_variant_id' => $variant->id,
                      'attribute_id' => $attributeMapIds['Fit Type'],
                      'attribute_value_id' => $fitTypeId,
                    ]);

                    ProductVariantAttribute::create([
                      'product_variant_id' => $variant->id,
                      'attribute_id' => $attributeMapIds['Size'],
                      'attribute_value_id' => $sizeId,
                    ]);

                    Inventory::create([
                      'product_id' => $product->id,
                      'product_variant_id' => $variant->id,
                      'quantity' => rand(5, 20),
                      'max_selling_quantity' => 5,
                      'threshold' => 5,
                      'alert_sent' => 0,
                      'alert_role_id' => 1,
                    ]);
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}
