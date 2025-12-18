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

class ProductSeeder extends Seeder
{
  public function __construct(protected FashionCategoryData $categoryData) {}

  /* ================= CARTESIAN PRODUCT ================= */
  private function cartesianProduct(array $arrays): array
  {
    $result = [[]];
    foreach ($arrays as $attrName => $values) {
      $tmp = [];
      foreach ($result as $combo) {
        foreach ($values as $v) {
          $tmp[] = array_merge($combo, [$attrName => $v]);
        }
      }
      $result = $tmp;
    }
    return $result;
  }

  public function run(): void
  {
    $nested = $this->categoryData->getNestedCategories();

    /* ================= COLOR IMAGES ================= */
    $colorImages = [
      'Red'   => ['Red1.webp', 'Red2.webp', 'Red3.webp'],
      'Blue'  => ['Blue1.webp', 'Blue2.webp', 'Blue3.webp'],
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

    /* ================= ATTRIBUTE MAP ================= */
    $attributeMap = [];
    $attributeMapIds = [];

    foreach (ProductAttribute::all() as $attr) {
      $attributeMapIds[$attr->name] = $attr->id;

      foreach (
        ProductAttributeValue::where('attribute_id', $attr->id)->get()
        as $val
      ) {
        $attributeMap[$attr->name][] = [
          'id' => $val->id,
          'value' => $val->value,
        ];
      }
    }

    /* ================= CATEGORY ATTRIBUTES ================= */
    $categoryAttributeMap = [
      'Dresses'       => ['Color', 'Size'],
      'Tops'          => ['Color', 'Size'],
      'Women Jackets' => ['Color', 'Size'],
      'Women Coats'   => ['Color', 'Size'],
      'Shirts'        => ['Color', 'Size'],
      'T-Shirts'      => ['Color', 'Size'],
      'Men Jackets'   => ['Color', 'Size'],
      'Men Coats'     => ['Color', 'Size'],
      'Bags'          => ['Color', 'Bag Type'],
      'Belts'         => ['Color', 'Belt Size'],
      'Sunglasses'    => ['Frame Color', 'Lens Type'],
      'Watches'       => ['Watch Type', 'Dial Color'],
      'default'       => ['Color', 'Size'],
    ];

    /* ================= MAIN LOOP ================= */
    foreach ($nested as $parentTitle => $childrenMap) {
      $parent = ProductCategory::where('title', $parentTitle)->first();
      if (!$parent) continue;

      foreach ($childrenMap as $childTitle => $grandchildren) {
        $child = ProductCategory::where('title', $childTitle)
          ->where('parent_id', $parent->id)
          ->first();
        if (!$child) continue;

        foreach ($grandchildren as $grandTitle) {
          $grandchild = ProductCategory::where('title', $grandTitle)
            ->where('parent_id', $child->id)
            ->first();
          if (!$grandchild) continue;

          for ($p = 1; $p <= 2; $p++) {

            /* ===== PRODUCT CREATION (UNCHANGED) ===== */
            $productName = "{$parentTitle} Fashion {$grandchild->title} Style {$p}";
            $existingProduct = Product::where('name', $productName)->first();

            if ($existingProduct) {
              $product = $existingProduct;
              $productSku = $product->sku;
            } else {
              $parentCode = strtoupper(substr($parentTitle, 0, 3));
              $catTitleClean = preg_replace(
                '/\b' . preg_quote($parentTitle, '/') . '\b/i',
                '',
                $grandchild->title
              );
              $catTitleClean = trim(preg_replace('/\s+/', ' ', $catTitleClean));
              $catCode = strtoupper(
                substr(preg_replace('/\W/', '', $catTitleClean ?: $grandchild->title), 0, 3)
              );
              $styleCode = "STYLE{$p}";
              $baseSku = "{$parentCode}-{$catCode}-{$styleCode}";

              $productSku = $baseSku;
              $suffix = 1;
              while (Product::where('sku', $productSku)->exists()) {
                $productSku = "{$baseSku}-{$suffix}";
                $suffix++;
              }

              $product = Product::create([
                'name' => $productName,
                'sku' => $productSku,
                'category_id' => $grandchild->id,
                'type' => 'variable',
                'status' => 1,
              ]);
            }

            /* ===== PRODUCT FILTERS (UNCHANGED) ===== */
            foreach ($attributeMapIds as $attrId) {
              ProductFilter::create([
                'product_id' => $product->id,
                'attribute_id' => $attrId,
              ]);
            }

            $appliedAttrs = $categoryAttributeMap[$grandchild->title]
              ?? $categoryAttributeMap['default'];

            $valuesForAttrs = [];
            foreach ($appliedAttrs as $attrName) {
              if (!empty($attributeMap[$attrName])) {
                $valuesForAttrs[$attrName] =
                  array_slice($attributeMap[$attrName], 0, 4);
              }
            }

            foreach ($this->cartesianProduct($valuesForAttrs) as $combo) {

              /* ===== VARIANT BUILD (AUTO, SAFE) ===== */
              $variantNameParts = [];
              $variantSkuParts  = [];
              $attributeDetails = [];

              foreach ($combo as $attrName => $val) {
                $attributeDetails[$attrName] = $val['value'];
                $variantNameParts[] = $val['value'];
                $variantSkuParts[] =
                  strtoupper(substr(preg_replace('/\W/', '', $val['value']), 0, 3));
              }

              $variantSku = $productSku . '-' . implode('-', $variantSkuParts);
              while (ProductVariant::where('sku', $variantSku)->exists()) {
                $variantSku .= '-1';
              }

              $variant = ProductVariant::create([
                'product_id' => $product->id,
                'name' => implode(' / ', $variantNameParts),
                'sku' => $variantSku,
                'attribute_details' => $attributeDetails,
                'regular_price' => rand(1000, 10000),
                'sale_price' => rand(800, 9000),
              ]);

              /* ===== PIVOT ATTRIBUTES ===== */
              foreach ($combo as $attrName => $val) {
                ProductVariantAttribute::create([
                  'product_variant_id' => $variant->id,
                  'attribute_id' => $attributeMapIds[$attrName],
                  'attribute_value_id' => $val['id'],
                ]);
              }

              /* ===== IMAGES (COLOR BASED) ===== */
              if (isset($combo['Color'])) {
                $color = $combo['Color']['value'];
                if (!empty($mediaGalleryMap[$color])) {
                  ProductVariantImages::create([
                    'product_variant_id' => $variant->id,
                    'media_gallery_id' =>
                    $mediaGalleryMap[$color][array_rand($mediaGalleryMap[$color])],
                    'is_default' => 1,
                  ]);
                }
              }

              /* ===== INVENTORY ===== */
              Inventory::create([
                'product_id' => $product->id,
                'product_variant_id' => $variant->id,
                'quantity' => rand(5, 50),
                'threshold' => 5,
                'max_selling_quantity' => 5,
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
