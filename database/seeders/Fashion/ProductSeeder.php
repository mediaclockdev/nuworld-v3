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
   * Cartesian product helper for arrays of attribute value sets.
   * $arrays should be like: ['Color' => [ ['value'=>'Red','id'=>1], ... ], 'Size' => [...]]
   */
  private function cartesianProduct(array $arrays): array
  {
    $result = [[]];
    foreach ($arrays as $attrName => $values) {
      $tmp = [];
      foreach ($result as $productCombo) {
        foreach ($values as $v) {
          $tmp[] = array_merge($productCombo, [$attrName => $v]);
        }
      }
      $result = $tmp;
    }
    return $result;
  }

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

    // Category => attributes mapping (exactly as you requested)
    $categoryAttributeMap = [

      // Women - Clothing
      'Dresses'         => ['Color', 'Size', 'Material'],
      'Tops'            => ['Color', 'Size', 'Material'],

      // Women - Outerwear (namespaced keys)
      'Women Jackets'   => ['Color', 'Size', 'Material'],
      'Women Coats'     => ['Color', 'Size', 'Material'],

      // Women - Accessories
      'Bags'            => ['Color', 'Material', 'Bag Type'],
      'Sunglasses'      => ['Frame Colour', 'Lens Type'],

      // Men - Clothing
      'Shirts'          => ['Color', 'Size', 'Material'],
      'T-Shirts'        => ['Color', 'Size', 'Material'],

      // Men - Outerwear
      'Men Jackets'     => ['Color', 'Size', 'Material'],
      'Men Coats'       => ['Color', 'Size', 'Material'],

      // Men - Accessories
      'Belts'           => ['Color', 'Material', 'Belt Size'],
      'Watches'         => ['Watch Type', 'Band Material', 'Dial Colour'],

      // Fallback
      'default'         => ['Color', 'Material', 'Size'],
    ];

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
            // ---- PRODUCT name format: "Women Fashion Jackets Style 1" ----
            $productName = "{$parentTitle} Fashion {$grandchild->title} Style {$p}";

            // If product already exists, use it (do not recreate)
            $existingProduct = Product::where('name', $productName)->first();
            if ($existingProduct) {
              $product = $existingProduct;
              $productSku = $product->sku;
            } else {
              // --- PRODUCT SKU generation per convention (robust) ---
              $parentCode = strtoupper(substr($parentTitle, 0, 3));     // WOM / MEN

              // remove parent words from grandchild title (e.g. "Women Jackets" -> "Jackets")
              $catTitleClean = preg_replace('/\b' . preg_quote($parentTitle, '/') . '\b/i', '', $grandchild->title);
              $catTitleClean = trim(preg_replace('/\s+/', ' ', $catTitleClean)); // normalize spaces
              if ($catTitleClean === '') {
                // fallback if removal emptied the title (defensive)
                $catTitleClean = $grandchild->title;
              }

              // take first 3 alphanumeric chars as category code
              $catCode = strtoupper(preg_replace('/[^A-Z0-9]/i', '', substr($catTitleClean, 0, 3)));
              if ($catCode === '') {
                $catCode = strtoupper(substr(preg_replace('/[^A-Z0-9]/', '', $grandchild->title), 0, 3)) ?: 'CAT';
              }

              $styleCode = "STYLE{$p}";
              $baseProductSku = "{$parentCode}-{$catCode}-{$styleCode}";

              // ensure SKU uniqueness: append numeric suffix if needed
              $productSku = $baseProductSku;
              $suffix = 1;
              while (Product::where('sku', $productSku)->exists()) {
                $productSku = $baseProductSku . '-' . $suffix;
                $suffix++;
              }

              $product = Product::create([
                'name' => $productName,
                'sku' => $productSku,
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
            }

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

            // ---- Resolve attributes for this category (path -> namespaced -> title -> default)
            $pathKey = "{$parentTitle} > {$childTitle} > {$grandchild->title}";
            $namespacedKey = "{$parentTitle} {$grandchild->title}"; // e.g. "Women Jackets" or "Men Jackets"

            if (isset($categoryAttributeMap[$pathKey])) {
              $appliedAttrs = $categoryAttributeMap[$pathKey];
            } elseif (isset($categoryAttributeMap[$namespacedKey])) {
              $appliedAttrs = $categoryAttributeMap[$namespacedKey];
            } elseif (isset($categoryAttributeMap[$grandchild->title])) {
              $appliedAttrs = $categoryAttributeMap[$grandchild->title];
            } else {
              $appliedAttrs = $categoryAttributeMap['default'];
            }

            // Build arrays of available attribute values (['value'=>name,'id'=>id])
            $valuesForAttrs = [];
            foreach ($appliedAttrs as $attrName) {
              if (!empty($attributeMap[$attrName])) {
                $vals = [];
                foreach ($attributeMap[$attrName] as $valName => $valId) {
                  $vals[] = ['value' => $valName, 'id' => $valId];
                }
                // limit per attribute to first 4 values to avoid explosion
                $valuesForAttrs[$attrName] = array_slice($vals, 0, 4);
              }
            }

            // Defensive: if nothing found (rare), fallback to Color if available or create a single variant
            if (empty($valuesForAttrs)) {
              if (!empty($attributeMap['Color'])) {
                $vals = [];
                foreach ($attributeMap['Color'] as $vn => $vid) {
                  $vals[] = ['value' => $vn, 'id' => $vid];
                }
                $valuesForAttrs['Color'] = array_slice($vals, 0, 3);
              } else {
                // create a single default variant and continue
                // PRODUCT SKU is $productSku, use it to build variant sku token
                $variantSkuTokenParts = ['DEF'];
                $variantSku = $productSku . '-' . implode('-', $variantSkuTokenParts);

                $variant = ProductVariant::create([
                  'product_id' => $product->id,
                  'name' => $productName . ' Variant',
                  'sku' => $variantSku,
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
            }

            // Build cartesian product and cap combos
            $productCombinations = $this->cartesianProduct($valuesForAttrs);
            $maxCombos = 40;
            if (count($productCombinations) > $maxCombos) {
              $productCombinations = array_slice($productCombinations, 0, $maxCombos);
            }

            // Create variants from combinations
            foreach ($productCombinations as $combo) {
              // Build readable variant name from chosen values
              $variantPieces = [];
              $variantSkuParts = [];
              foreach ($combo as $attrName => $val) {
                $variantPieces[] = $val['value'];
                // take first 3 chars uppercase for SKU token (non-empty)
                $clean = preg_replace('/[^A-Za-z0-9]/', '', strtoupper(substr($val['value'], 0, 3)));
                $variantSkuParts[] = $clean ?: 'X';
              }

              // FINAL VARIANT NAME format:
              // "Women Fashion Jackets - Style 1 Red M Cotton"
              $variantName = "{$parentTitle} Fashion {$grandchild->title} - Style {$p} " . implode(' ', $variantPieces);

              // VARIANT SKU: PRODUCT-SKU + '-' + tokens (e.g. WOM-JAC-STYLE1-RED-M-COT)
              $baseVariantSku = $productSku . '-' . implode('-', $variantSkuParts);

              // ensure variant SKU uniqueness
              $variantSku = $baseVariantSku;
              $vSuffix = 1;
              while (ProductVariant::where('sku', $variantSku)->exists()) {
                $variantSku = $baseVariantSku . '-' . $vSuffix;
                $vSuffix++;
              }

              $variant = ProductVariant::create([
                'product_id' => $product->id,
                'name' => $variantName,
                'sku' => $variantSku,
                'regular_price' => $regularPrice = rand(1100, 11000),
                'sale_price' => rand(1000, $regularPrice - 1),
              ]);

              // Attach media: prefer Color mapping if present in combo
              $mediaId = null;
              if (isset($combo['Color'])) {
                $colorName = $combo['Color']['value'];
                $mediaIds = $mediaGalleryMap[$colorName] ?? [];
                $mediaId = !empty($mediaIds) ? $mediaIds[array_rand($mediaIds)] : (MediaGallery::inRandomOrder()->first()->id ?? null);
              } else {
                $mediaId = MediaGallery::inRandomOrder()->first()->id ?? null;
              }

              if ($mediaId) {
                ProductVariantImages::create([
                  'product_variant_id' => $variant->id,
                  'media_gallery_id' => $mediaId,
                  'is_default' => 1,
                ]);
              }

              // Attach variant attributes to pivot table
              foreach ($combo as $attrName => $val) {
                if (!isset($attributeMapIds[$attrName])) continue;
                ProductVariantAttribute::create([
                  'product_variant_id' => $variant->id,
                  'attribute_id' => $attributeMapIds[$attrName],
                  'attribute_value_id' => $val['id'],
                ]);
              }

              // Inventory
              Inventory::create([
                'product_id' => $product->id,
                'product_variant_id' => $variant->id,
                'quantity' => rand(1, 20),
                'max_selling_quantity' => 5,
                'threshold' => 5,
                'alert_sent' => 0,
                'alert_role_id' => 1,
              ]);
            } // end foreach combos
          } // end for p
        } // end foreach grandchild
      } // end foreach child
    } // end foreach parent
  } // end run
}
