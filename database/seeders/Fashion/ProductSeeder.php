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

    // color -> images map (kept from your previous seeder)
    $colorImages = [
      'Red'   => ['Red1.webp', 'Red2.webp', 'Red3.webp'],
      'Blue'  => ['Blue1.webp', 'Blue2.webp', 'Blue3.webp'],
      'Green' => ['Green1.webp', 'Green2.webp', 'Green3.webp'],
      'Black' => ['Black1.webp', 'Black2.webp', 'Black3.webp'],
    ];

    // Create media gallery rows (idempotent firstOrCreate) and build map
    $mediaGalleryMap = [];
    foreach ($colorImages as $color => $images) {
      foreach ($images as $image) {
        $media = MediaGallery::firstOrCreate(
          ['file_name' => $image],
          ['file_type' => 'image/jpeg']
        );
        $mediaGalleryMap[$color][] = $media->id;
      }
    }

    // Build attribute maps from DB: attributeName => [valueName => id], and attributeName => attribute_id
    $attributeMap = [];    // e.g. 'Color' => ['Red' => 1, ...]
    $attributeMapIds = []; // e.g. 'Color' => 1
    $dbAttributes = ProductAttribute::all();
    foreach ($dbAttributes as $attr) {
      $attributeMapIds[$attr->name] = $attr->id;
      $vals = ProductAttributeValue::where('attribute_id', $attr->id)->pluck('value', 'id')->toArray();
      $map = [];
      foreach ($vals as $id => $name) {
        $map[$name] = $id;
      }
      $attributeMap[$attr->name] = $map;
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
            // ---- Fashion-Wireframes product content (keeps your original HTML) ----
            $productName = "Fashion Wireframe - {$grandchild->title} Style {$p}";

            // idempotent guard: skip if product with same name already exists
            if (Product::where('name', $productName)->exists()) {
              continue;
            }

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

            // Store Product Filter attributes (unchanged) - idempotent creation
            $allAttributes = ProductAttribute::all();
            $filterAttributes = $allAttributes->pluck('name')->toArray();
            foreach ($filterAttributes as $filterAttrName) {
              if (isset($attributeMapIds[$filterAttrName])) {
                ProductFilter::firstOrCreate([
                  'product_id'   => $product->id,
                  'attribute_id' => $attributeMapIds[$filterAttrName],
                ]);
              }
            }

            //
            // --- Resolve attributes for this category (path -> namespaced -> title -> default)
            //
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
              foreach ($combo as $attrName => $val) {
                $variantPieces[] = $val['value'];
              }
              $variantName = $productName . ' ' . implode(' ', $variantPieces);

              $variant = ProductVariant::create([
                'product_id' => $product->id,
                'name' => $variantName,
                'sku' => strtoupper(Str::slug($variantName . '-' . substr(uniqid(), -4))),
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
