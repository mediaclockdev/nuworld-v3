<?php

namespace Database\Seeders\Furniture;

use Illuminate\Database\Seeder;
use App\Models\{Product, ProductCategory, ProductVariant, ProductVariantImages, ProductVariantAttribute, Inventory, MediaGallery, ProductAttribute, ProductAttributeValue, ProductFilter};
use App\Traits\Seeders\FurnitureCategoryData;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{

  public function __construct(protected FurnitureCategoryData $categoryData) {}
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $levels               = $this->categoryData->getCategoryLevels();
    $parentCategories     = $levels['parentCategories'];
    $childCategories      = $levels['childCategories'];
    $grandchildCategories = $levels['grandchildCategories'];

    $colorImages = [
      'Red' => ['Red1.webp', 'Red2.webp', 'Red3.webp'],
      'Blue' => ['Blue1.webp', 'Blue2.webp', 'Blue3.webp'],
      'Green' => ['Green1.webp', 'Green2.webp', 'Green3.webp'],
      'Brown' => ['Brown1.webp', 'Brown2.webp', 'Brown3.webp'],
    ];

    $mediaGalleryMap = [];

    foreach ($colorImages as $color => $images) {
      foreach ($images as $image) {
        $media = MediaGallery::create([
          'file_name' => $image,
          'file_type' => 'image/jpeg',
        ]);
        $mediaGalleryMap[$color][] = $media->id;
      }
    }

    $attributes      = [];
    $attributeMap    = [];
    $attributeMapIds = [];

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
            $productName = "$p Seater {$grandchild->title}";
            $product = Product::create([
              'name' => $productName,
              'sku' => strtoupper(Str::slug($productName . '-' . substr(uniqid(), -4))),
              'category_id' => $grandchild->id,
              'type' => 'variable',
              'status' => 1,
               'product_details' => "
                <div class=\"d-grid grid-2\">
                    <div>
                        <h4 class=\"font18 c--blackc fw-medium m-0\">Brand</h4>
                        <p class=\"c--gry font18 m-0\">Wooden from Mayuri</p>
                    </div>
                    <div>
                        <h4 class=\"font18 c--blackc fw-medium m-0\">Category</h4>
                        <p class=\"c--gry font18 m-0\">{$grandchild->title}</p>
                    </div>
                    <div>
                        <h4 class=\"font18 c--blackc fw-medium m-0\">Seating Capacity</h4>
                        <p class=\"c--gry font18 m-0\">{$p} Seater</p>
                    </div>
                    <div>
                        <h4 class=\"font18 c--blackc fw-medium m-0\">Warranty</h4>
                        <p class=\"c--gry font18 m-0\">1-year warranty</p>
                    </div>
                    <div>
                        <h4 class=\"font18 c--blackc fw-medium m-0\">Dimensions</h4>
                        <p class=\"c--gry font18 m-0\">W 80 x D 35 x H 32 inches</p>
                    </div>
                    <div>
                        <h4 class=\"font18 c--blackc fw-medium m-0\">Weight</h4>
                        <p class=\"c--gry font18 m-0\">Up to 300 kg</p>
                    </div>
                </div>
            ",
              'specifications' => "Seating Capacity: {$p} Seater. Material: Solid Wood Frame with Premium Fabric. Dimensions: W 80 x D 35 x H 32 inches. Color Options: Red, Blue, Green, Brown. Weight Capacity: Up to 300 kg.",
              'care_maintenance' => "Clean with a soft dry cloth. Avoid direct sunlight or moisture. Vacuum fabric upholstery regularly.",
              'warranty' => "1-year warranty on manufacturing defects. Does not cover wear and tear or misuse.",
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


            foreach ($attributeMap['Color'] as $colorName => $colorId) {
              foreach ($attributeMap['Material'] as $materialName => $materialId) {
                $variantName = "$productName $colorName $materialName";

                $variant = ProductVariant::create([
                  'product_id' => $product->id,
                  'name' => $variantName,
                  'sku' => strtoupper(Str::slug($variantName . '-' . substr(uniqid(), -4))),
                  'regular_price' => $regularPrice = rand(1100, 11000),
                  'sale_price' => rand(1000, $regularPrice - 1),
                ]);

                $mediaIds = $mediaGalleryMap[$colorName];
                $randomMediaId = $mediaIds[array_rand($mediaIds)];

                ProductVariantImages::create([
                  'product_variant_id' => $variant->id,
                  'media_gallery_id' => $randomMediaId,
                  'is_default' => 1
                ]);

                ProductVariantAttribute::create([
                  'product_variant_id' => $variant->id,
                  'attribute_id' => $attributeMapIds['Color'],
                  'attribute_value_id' => $colorId,
                ]);

                ProductVariantAttribute::create([
                  'product_variant_id' => $variant->id,
                  'attribute_id' => $attributeMapIds['Material'],
                  'attribute_value_id' => $materialId,
                ]);

                Inventory::create([
                  'product_id' => $product->id,
                  'product_variant_id' => $variant->id,
                  'quantity' => rand(1, 5),
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
