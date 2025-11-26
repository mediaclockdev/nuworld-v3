<?php

namespace Database\Seeders\Fashion;

use Illuminate\Database\Seeder;
use App\Models\{
  Product,
  ProductCategory,
  ProductVariant,
  ProductVariantImages,
  ProductVariantAttribute,
  Inventory,
  MediaGallery,
  ProductAttribute,
  ProductAttributeValue,
  ProductFilter
};
use App\Traits\Seeders\FashionCategoryData;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
  public function __construct(protected FashionCategoryData $categoryData) {}

  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Use nested categories to avoid collisions between same-named children under different parents
    $nested = $this->categoryData->getNestedCategories();

    // <-- kept exactly as you requested -->
    $colorImages = [
      'Red' => ['Red1.webp', 'Red2.webp', 'Red3.webp'],
      'Blue' => ['Blue1.webp', 'Blue2.webp', 'Blue3.webp'],
      'Green' => ['Green1.webp', 'Green2.webp', 'Green3.webp'],
      'Black' => ['Black1.webp', 'Black2.webp', 'Black3.webp'],
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

    // Iterate nested structure: Parent -> Child -> Grandchildren
    foreach ($nested as $parentTitle => $childrenMap) {
      $parent = ProductCategory::where('title', $parentTitle)->first();
      if (!$parent) continue;

      foreach ($childrenMap as $childTitle => $grandchildren) {
        $child = ProductCategory::where('title', $childTitle)->where('parent_id', $parent->id)->first();
        if (!$child) continue;

        foreach ($grandchildren as $g => $grandTitle) {
          $grandchild = ProductCategory::where('title', $grandTitle)->where('parent_id', $child->id)->first();
          if (!$grandchild) continue;

          // Create 2 products per grandchild (as in your original seeder)
          for ($p = 1; $p <= 2; $p++) {
            // ---- Fashion-Wireframes product content (replaces furniture product text) ----
            $productName = "Fashion Wireframe - {$grandchild->title} Style {$p}";
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
                                        <p class=\"c--gry font18 m-0\">Mayuri Fashion</p>
                                    </div>
                                    <div>
                                        <h4 class=\"font18 c--blackc fw-medium m-0\">Category</h4>
                                        <p class=\"c--gry font18 m-0\">{$grandchild->title}</p>
                                    </div>
                                    <div>
                                        <h4 class=\"font18 c--blackc fw-medium m-0\">Style</h4>
                                        <p class=\"c--gry font18 m-0\">Style {$p}</p>
                                    </div>
                                    <div>
                                        <h4 class=\"font18 c--blackc fw-medium m-0\">Return Policy</h4>
                                        <p class=\"c--gry font18 m-0\">7-day return policy</p>
                                    </div>
                                    <div>
                                        <h4 class=\"font18 c--blackc fw-medium m-0\">Fit</h4>
                                        <p class=\"c--gry font18 m-0\">Regular Fit</p>
                                    </div>
                                    <div>
                                        <h4 class=\"font18 c--blackc fw-medium m-0\">Made In</h4>
                                        <p class=\"c--gry font18 m-0\">India</p>
                                    </div>
                                </div>
                            ",
              'specifications' => "Category: {$grandchild->title}. Material: Assorted Fabrics. Available Colors: Red, Blue, Green, Brown. Sizes: XS, S, M, L, XL (where applicable).",
              'care_maintenance' => "Follow garment care label. Machine wash cold, gentle cycle. Do not bleach. Dry flat.",
              'warranty' => "30-day manufacturer warranty on defects.",
            ]);

            // Store Product Filter attributes (unchanged)
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

            // ---- Variant generation: Color x Material (and Size if present) ----
            $colorMap = $attributeMap['Color'] ?? [];
            $materialMap = $attributeMap['Material'] ?? [];
            $sizeMap = $attributeMap['Size'] ?? [];

            // Defensive: if Color or Material are missing, fall back to creating a default variant
            if (empty($colorMap) || empty($materialMap)) {
              // create a default single variant and attach any first-available attributes
              $variant = ProductVariant::create([
                'product_id' => $product->id,
                'name' => $productName . ' Variant',
                'sku' => strtoupper(Str::slug($productName . '-' . substr(uniqid(), -4))),
                'regular_price' => $regularPrice = rand(500, 5000),
                'sale_price' => rand(400, max(401, $regularPrice - 1)),
              ]);

              // attach a random media from the existing media map
              $allMedia = [];
              foreach ($mediaGalleryMap as $arr) {
                $allMedia = array_merge($allMedia, $arr);
              }
              $randomMediaId = $allMedia[array_rand($allMedia)] ?? null;
              if ($randomMediaId) {
                ProductVariantImages::create([
                  'product_variant_id' => $variant->id,
                  'media_gallery_id' => $randomMediaId,
                  'is_default' => 1
                ]);
              }

              // attach any available attribute first-values
              foreach (['Color', 'Material', 'Size'] as $maybe) {
                if (!empty($attributeMap[$maybe])) {
                  $firstVal = array_values($attributeMap[$maybe])[0];
                  ProductVariantAttribute::create([
                    'product_variant_id' => $variant->id,
                    'attribute_id' => $attributeMapIds[$maybe],
                    'attribute_value_id' => $firstVal,
                  ]);
                }
              }

              Inventory::create([
                'product_id' => $product->id,
                'product_variant_id' => $variant->id,
                'quantity' => rand(1, 5),
                'max_selling_quantity' => 5,
                'threshold' => 5,
                'alert_sent' => 0,
                'alert_role_id' => 1,
              ]);

              continue;
            }

            // Normal path: Color x Material (and Size if present)
            foreach ($colorMap as $colorName => $colorId) {
              foreach ($materialMap as $materialName => $materialId) {
                if (!empty($sizeMap)) {
                  foreach ($sizeMap as $sizeName => $sizeId) {
                    $variantName = "$productName $colorName $materialName $sizeName";
                    $variant = ProductVariant::create([
                      'product_id' => $product->id,
                      'name' => $variantName,
                      'sku' => strtoupper(Str::slug($variantName . '-' . substr(uniqid(), -4))),
                      'regular_price' => $regularPrice = rand(1100, 11000),
                      'sale_price' => rand(1000, $regularPrice - 1),
                    ]);

                    $mediaIds = $mediaGalleryMap[$colorName] ?? [];
                    $randomMediaId = !empty($mediaIds) ? $mediaIds[array_rand($mediaIds)] : (MediaGallery::inRandomOrder()->first()->id ?? null);

                    if ($randomMediaId) {
                      ProductVariantImages::create([
                        'product_variant_id' => $variant->id,
                        'media_gallery_id' => $randomMediaId,
                        'is_default' => 1
                      ]);
                    }

                    // attach attributes: Color, Material, Size
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

                    ProductVariantAttribute::create([
                      'product_variant_id' => $variant->id,
                      'attribute_id' => $attributeMapIds['Size'],
                      'attribute_value_id' => $sizeId,
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
                } else {
                  // No size: create single variant per color+material
                  $variantName = "$productName $colorName";

                  $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $variantName,
                    'sku' => strtoupper(Str::slug($variantName . '-' . substr(uniqid(), -4))),
                    'regular_price' => $regularPrice = rand(1100, 11000),
                    'sale_price' => rand(1000, $regularPrice - 1),
                  ]);

                  $mediaIds = $mediaGalleryMap[$colorName] ?? [];
                  $randomMediaId = !empty($mediaIds) ? $mediaIds[array_rand($mediaIds)] : (MediaGallery::inRandomOrder()->first()->id ?? null);

                  if ($randomMediaId) {
                    ProductVariantImages::create([
                      'product_variant_id' => $variant->id,
                      'media_gallery_id' => $randomMediaId,
                      'is_default' => 1
                    ]);
                  }

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
          } // end for p
        } // end foreach grandchild
      } // end foreach child
    } // end foreach parent
  }
}
