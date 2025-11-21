<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductVariant;
use App\Models\ProductVariantAttribute;
use App\Models\ProductCategory;

class ProductModuleSeeder extends Seeder
{
  public function run(): void
  {
    DB::transaction(function () {
      // // Delete in proper order to avoid foreign key constraints
      // DB::table('product_variant_attributes')->delete();
      // DB::table('product_variants')->delete();
      // DB::table('products')->delete();
      // DB::table('product_attribute_values')->delete();
      // DB::table('product_attributes')->delete();
      // DB::table('product_categories')->delete();

      // // --- CATEGORIES ---
      // $categories = ['Sofa', 'Bed', 'Wardrobe', 'Dining Table', 'Chair'];

      // foreach ($categories as $index => $title) {
      //   ProductCategory::create([
      //     'title' => $title,
      //     'slug' => Str::slug($title),
      //     'tax' => collect([5, 12, 18])->random(),
      //     'sequence' => $index + 1,
      //     'status' => 1,
      //     'created_by' => 1,
      //   ]);
      // }

      // // --- ATTRIBUTES ---
      // $attributeMap = [];

      // // Create Color attribute
      // $colorAttribute = ProductAttribute::create([
      //   'name' => 'Color',
      //   'sequence' => 1,
      //   'status' => 1,
      //   'created_by' => 1,
      // ]);

      // $colors = [
      //   ['value' => 'Red', 'hex' => '#FF0000'],
      //   ['value' => 'Blue', 'hex' => '#0000FF'],
      //   ['value' => 'Green', 'hex' => '#008000'],
      //   ['value' => 'Brown', 'hex' => '#A52A2A'],
      //   ['value' => 'Black', 'hex' => '#000000'],
      // ];

      // $colorValueIds = [];
      // foreach ($colors as $i => $color) {
      //   $value = ProductAttributeValue::create([
      //     'attribute_id' => $colorAttribute->id,
      //     'value' => $color['value'],
      //     'value_details' => $color['hex'],
      //     'sequence' => $i + 1,
      //     'status' => 1,
      //     'created_by' => 1,
      //   ]);
      //   $colorValueIds[] = $value->id;
      // }
      // $attributeMap['Color'] = ['id' => $colorAttribute->id, 'values' => $colorValueIds];

      // // Add Size and Material
      // $otherAttributes = [
      //   'Size' => ['Small', 'Medium', 'Large'],
      //   'Material' => ['Wood', 'Metal', 'Plastic', 'Leather']
      // ];

      // foreach ($otherAttributes as $attr => $values) {
      //   $attribute = ProductAttribute::create([
      //     'name' => $attr,
      //     'sequence' => 1,
      //     'status' => 1,
      //     'created_by' => 1,
      //   ]);
      //   $attributeMap[$attr] = ['id' => $attribute->id, 'values' => []];

      //   foreach ($values as $i => $val) {
      //     $value = ProductAttributeValue::create([
      //       'attribute_id' => $attribute->id,
      //       'value' => $val,
      //       'sequence' => $i + 1,
      //       'status' => 1,
      //       'created_by' => 1,
      //     ]);
      //     $attributeMap[$attr]['values'][] = $value->id;
      //   }
      // }

      // // --- PRODUCTS & VARIANTS ---
      // $sampleProducts = [
      //   'Sofa' => ['Athens 3-Seater Sofa', 'Nora Recliner', 'Bergen Sectional Sofa', 'Oslo Sofa Set'],
      //   'Bed' => ['Caspian King Size Bed', 'Florence Queen Bed', 'Aurora Hydraulic Bed', 'Milan Storage Bed'],
      //   'Wardrobe' => ['Veneto 3-Door Wardrobe', 'Nova Sliding Wardrobe', 'Tuscany 2-Door Almirah', 'Madrid Wardrobe'],
      //   'Dining Table' => ['Luna 6-Seater Dining Set', 'Ethan Round Table', 'Harper Glass Dining Table', 'Orion Wooden Dining Set'],
      //   'Chair' => ['Zion Lounge Chair', 'Bristol Office Chair', 'Camden Rocking Chair', 'Delta Accent Chair']
      // ];

      // foreach ($sampleProducts as $categoryTitle => $productNames) {
      //   $categoryId = ProductCategory::where('title', $categoryTitle)->value('id');

      //   foreach ($productNames as $name) {
      //     $product = Product::create([
      //       'name' => $name,
      //       'description' => "Beautiful and durable $categoryTitle - $name.",
      //       'sku' => strtoupper(Str::random(8)),
      //       'type' => 'variable',
      //       'category_id' => $categoryId,
      //       'status' => 1,
      //       'created_by' => 1,
      //     ]);

      //     $variantCount = rand(1, 3);
      //     $isColorOnlyVariant = rand(0, 1);

      //     if ($isColorOnlyVariant) {
      //       $sizeId = collect($attributeMap['Size']['values'])->random();
      //       $materialId = collect($attributeMap['Material']['values'])->random();
      //       $sizeValue = ProductAttributeValue::find($sizeId)->value;
      //       $materialValue = ProductAttributeValue::find($materialId)->value;

      //       $colorIds = collect($attributeMap['Color']['values'])->random(rand(2, 4));

      //       foreach ($colorIds as $colorId) {
      //         $colorValue = ProductAttributeValue::find($colorId)->value;

      //         $variant = ProductVariant::create([
      //           'product_id' => $product->id,
      //           'name' => '1234',
      //           'regular_price' => rand(2000, 10000),
      //           'sale_price' => rand(1500, 9000),
      //           'sale_start_date' => now(),
      //           'sale_end_date' => now()->addDays(10),
      //           'sku' => strtoupper(Str::random(10)),
      //           'created_by' => 1,
      //         ]);

      //         ProductVariantAttribute::insert([
      //           [
      //             'product_variant_id' => $variant->id,
      //             'attribute_id' => $attributeMap['Color']['id'],
      //             'attribute_value_id' => $colorId,
      //           ],
      //           [
      //             'product_variant_id' => $variant->id,
      //             'attribute_id' => $attributeMap['Size']['id'],
      //             'attribute_value_id' => $sizeId,
      //           ],
      //           [
      //             'product_variant_id' => $variant->id,
      //             'attribute_id' => $attributeMap['Material']['id'],
      //             'attribute_value_id' => $materialId,
      //           ],
      //         ]);

      //         $variant->update([
      //           'name' => "$colorValue $sizeValue $materialValue $name"
      //         ]);
      //       }
      //     } else {
      //       foreach (range(1, $variantCount) as $vIndex) {
      //         $variant = ProductVariant::create([
      //           'product_id' => $product->id,
      //           'name' => 'Product ' . $vIndex,
      //           'regular_price' => rand(2000, 10000),
      //           'sale_price' => rand(1500, 9000),
      //           'sale_start_date' => now(),
      //           'sale_end_date' => now()->addDays(10),
      //           'sku' => strtoupper(Str::random(10)),
      //           'created_by' => 1,
      //         ]);

      //         $variantAttrValues = [];

      //         foreach ($attributeMap as $attr => $data) {
      //           $valueId = collect($data['values'])->random();
      //           $value = ProductAttributeValue::find($valueId);

      //           ProductVariantAttribute::create([
      //             'product_variant_id' => $variant->id,
      //             'attribute_id' => $data['id'],
      //             'attribute_value_id' => $valueId,
      //           ]);

      //           $variantAttrValues[] = $value->value;
      //         }

      //         $variantName = implode(' ', $variantAttrValues) . ' ' . $name;
      //         $variant->update(['name' => $variantName]);
      //       }
      //     }
      //   }
      // }
    });

    $this->command->info('✅ Product module seed complete!');
  }
}
