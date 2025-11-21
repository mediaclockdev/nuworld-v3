<?php

namespace Database\Seeders\Sunglasses;

use App\Traits\Seeders\SunglassesCategoryData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{Product, ProductCategory, ProductVariant, ProductVariantImages, ProductVariantAttribute, Inventory, MediaGallery, ProductAttribute, ProductAttributeValue, ProductFilter};
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
  public function __construct(protected SunglassesCategoryData $categoryData) {}
  /**
   * Run the database seeds.
   */
  public function run(): void
  {

    $levels               = $this->categoryData->getCategoryLevels();
    $parentCategories     = $levels['parentCategories'];
    $childCategories      = $levels['childCategories'];
    $grandchildCategories = $levels['grandchildCategories'];

    foreach (range(1, 5) as $i) {
      MediaGallery::create([
        'file_name' => 's-p' . $i . '.webp',
        'file_type' => 'image/webp',
      ]);
    }

    /*  $attributes = [
      'Lens Color'     => ['Black', 'Blue', 'Green', 'Golden'],
      'Frame Material' => ['Plastic', 'Metal', 'Wood'],
      'Lens Type'      => ['Polarized', 'Photochromic', 'Gradient'],
    ]; */

    $attributeMap    = [];
    $attributes      = [];
    $attributeMapIds = [];

    /*  foreach ($attributes as $attrName => $values) {
      $attr = ProductAttribute::where('name', $attrName)->first();
      if (!$attr) continue;

      $attributeMapIds[$attrName] = $attr->id;

      foreach ($values as $val) {
        $value = ProductAttributeValue::where('value', $val)->where('attribute_id', $attr->id)->first();
        if (!$value) continue;
        $attributeMap[$attrName][$val] = $value->id;
      }
    } */

    $dbAttributes = ProductAttribute::all();
    foreach ($dbAttributes as $attr) {
      $values                       = ProductAttributeValue::where('attribute_id', $attr->id)->pluck('value')->toArray();
      $attributes[$attr->name]      = $values;
      $attributeMapIds[$attr->name] = $attr->id;
      $attributeMap[$attr->name]    = [];

      foreach ($values as $val) {
        $value = ProductAttributeValue::where('attribute_id', $attr->id)->where('value', $val)->first();
        if ($value) {
          $attributeMap[$attr->name][$val] = $value->id;
        }
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
            $productName = "$grandTitle Glasses $p";
            $product = Product::create([
            'name' => $productName,
            'sku' => strtoupper(Str::slug($productName . '-' . substr(uniqid(), -4))),
            'category_id' => $grandchild->id,
            'type' => 'variable',
            'status' => 1,
            'product_details' => '
              <div class="d-grid grid-2">
                  <div>
                      <h4 class="font18 c--blackc fw-medium m-0">Brand</h4>
                      <p class="c--gry font18 m-0">VisionCraft</p>
                  </div>
                  <div>
                      <h4 class="font18 c--blackc fw-medium m-0">Category</h4>
                      <p class="c--gry font18 m-0">' . $grandTitle . '</p>
                  </div>
                  <div>
                      <h4 class="font18 c--blackc fw-medium m-0">Frame Material</h4>
                      <p class="c--gry font18 m-0">Plastic / Metal / Wood</p>
                  </div>
                  <div>
                      <h4 class="font18 c--blackc fw-medium m-0">Lens Type</h4>
                      <p class="c--gry font18 m-0">Polarized / Photochromic / Gradient</p>
                  </div>
                  <div>
                      <h4 class="font18 c--blackc fw-medium m-0">Lens Color Options</h4>
                      <p class="c--gry font18 m-0">Black, Blue, Green, Golden</p>
                  </div>
                  <div>
                      <h4 class="font18 c--blackc fw-medium m-0">UV Protection</h4>
                      <p class="c--gry font18 m-0">100% UVA & UVB Protection</p>
                  </div>
                  <div>
                      <h4 class="font18 c--blackc fw-medium m-0">Frame Shape</h4>
                      <p class="c--gry font18 m-0">Unisex Classic Design</p>
                  </div>
                  <div>
                      <h4 class="font18 c--blackc fw-medium m-0">Weight</h4>
                      <p class="c--gry font18 m-0">30 grams</p>
                  </div>
                  <div>
                      <h4 class="font18 c--blackc fw-medium m-0">Warranty</h4>
                      <p class="c--gry font18 m-0">6 Months Limited Warranty</p>
                  </div>
                  <div>
                      <h4 class="font18 c--blackc fw-medium m-0">SKU</h4>
                      <p class="c--gry font18 m-0">' . strtoupper(Str::slug($productName . '-' . substr(uniqid(), -4))) . '</p>
                  </div>
              </div>
          ',

            'specifications' => "Lens Color: Black, Blue, Green, Golden. Frame Material: Plastic, Metal, Wood. Lens Type: Polarized, Photochromic, Gradient. UV Protection: 100% UVA & UVB. Frame Shape: Unisex Classic Design. Weight: 30 grams.",
            'care_maintenance' => "Clean lenses with a microfiber cloth. Avoid chemicals and extreme heat. Store in the protective case when not in use.",
            'warranty' => "6-month warranty against manufacturing defects. Does not cover scratches or damage from misuse.",
            'care_maintenance' => "Wipe lenses with a microfiber cloth. Avoid contact with water, chemicals, or extreme heat. Store in the provided protective case when not in use.",
            'warranty' => "6-month limited warranty covering only manufacturing defects. Scratches or damage from mishandling are not covered.",
          ]);


            // Store Product Filter attributes
            $allAttributes    = ProductAttribute::all();
            $filterAttributes = $allAttributes->pluck('name')->toArray();
            foreach ($filterAttributes as $filterAttrName) {
              if (isset($attributeMapIds[$filterAttrName])) {
                ProductFilter::create([
                  'product_id'   => $product->id,
                  'attribute_id' => $attributeMapIds[$filterAttrName],
                ]);
              }
            }


            foreach ($attributeMap['Lens Color'] as $colorName => $colorId) {
              foreach ($attributeMap['Frame Material'] as $materialName => $materialId) {
                foreach ($attributeMap['Lens Type'] as $lensTypeName => $lensTypeId) {

                  $variantName = "$productName $colorName $materialName $lensTypeName";

                  $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $variantName,
                    'sku' => strtoupper(Str::slug($variantName . '-' . substr(uniqid(), -4))),
                    'regular_price' => $regularPrice = rand(500, 10000),
                    'sale_price' => rand(400, $regularPrice - 1),
                  ]);

                  ProductVariantImages::create([
                    'product_variant_id' => $variant->id,
                    'media_gallery_id' => DB::table('media_galleries')->inRandomOrder()->first()->id,
                    'is_default' => 1
                  ]);

                  ProductVariantAttribute::create([
                    'product_variant_id' => $variant->id,
                    'attribute_id' => $attributeMapIds['Lens Color'],
                    'attribute_value_id' => $colorId,
                  ]);

                  ProductVariantAttribute::create([
                    'product_variant_id' => $variant->id,
                    'attribute_id' => $attributeMapIds['Frame Material'],
                    'attribute_value_id' => $materialId,
                  ]);

                  ProductVariantAttribute::create([
                    'product_variant_id' => $variant->id,
                    'attribute_id' => $attributeMapIds['Lens Type'],
                    'attribute_value_id' => $lensTypeId,
                  ]);

                  Inventory::create([
                    'product_id' => $product->id,
                    'product_variant_id' => $variant->id,
                    'quantity' => rand(10, 20),
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
