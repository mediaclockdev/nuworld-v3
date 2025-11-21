<?php

namespace Database\Seeders\Grocery;

use App\Traits\Seeders\GroceryCategoryData;
use Illuminate\Database\Seeder;
use App\Models\{Product, ProductCategory, ProductVariant, ProductVariantImages, ProductVariantAttribute, Inventory, MediaGallery, ProductAttribute, ProductAttributeValue, ProductFilter};
use App\Traits\BaseCategoryDataTrait;
// use Database\Seeders\BaseFunctions\BaseFunctions;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
  public function __construct(protected GroceryCategoryData $categoryData) {}

  public function run(): void
  {
    $levels               = $this->categoryData->getCategoryLevels();
    $parentCategories     = $levels['parentCategories'];
    $childCategories      = $levels['childCategories'];
    $grandchildCategories = $levels['grandchildCategories'];

    // Create media gallery entries
    /* foreach (range(1, 14) as $i) {
      MediaGallery::create([
        'file_name' => 'gp' . $i . '.webp',
        'file_type' => 'image/webp',
      ]);
    } */


    $colorImagess = [
      'Apples'   => ['Apples1.webp', 'Apples2.webp', 'Apples3.webp', 'Apples4.webp', 'Apples5.webp'],
      'Avocados' => ['Avocados1.webp', 'Avocados2.webp', 'Avocados3.webp', 'Avocados4.webp', 'Avocados5.webp'],
      'Citrus' => ['Citrus1.webp', 'Citrus2.webp', 'Citrus3.webp', 'Citrus4.webp', 'Citrus5.webp'],
      /* 'Bananas & Plantains' => ['BananasPlantains1.webp', 'BananasPlantains2.webp', 'BananasPlantains3.webp', 'BananasPlantains4.webp', 'BananasPlantains5.webp'],
      'Beans, Peas & Corn' => ['BeansPeasCorn1.webp', 'BeansPeasCorn2.webp', 'BeansPeasCorn3.webp', 'BeansPeasCorn4.webp', 'BeansPeasCorn5.webp'],
      'Broccoli & Cauliflower' => ['BroccoliCauliflower1.webp', 'BroccoliCauliflower2.webp', 'BroccoliCauliflower3.webp', 'BroccoliCauliflower4.webp', 'BroccoliCauliflower5.webp'],
      'Garlic, Onions & Leeks' => ['GarlicOnionsLeeks1.webp', 'GarlicOnionsLeeks2.webp', 'GarlicOnionsLeeks3.webp', 'GarlicOnionsLeeks4.webp', 'GarlicOnionsLeeks5.webp'],
      'Grapes' => ['Grapes1.webp', 'Grapes2.webp', 'Grapes3.webp', 'Grapes4.webp', 'Grapes5.webp'],
      'Herbs' => ['Herbs1.webp', 'Herbs2.webp', 'Herbs3.webp', 'Herbs4.webp', 'Herbs5.webp'],
      'Lettuce & Greens' => ['LettuceGreens1.webp', 'LettuceGreens2.webp', 'LettuceGreens3.webp', 'LettuceGreens4.webp', 'LettuceGreens5.webp'],
      'Melons' => ['Melons1.webp', 'Melons2.webp', 'Melons3.webp', 'Melons4.webp', 'Melons5.webp'],
      'Mushrooms' => ['Mushrooms1.webp', 'Mushrooms2.webp', 'Mushrooms3.webp', 'Mushrooms4.webp', 'Mushrooms5.webp'],
      'Other Fruit' => ['OtherFruit1.webp', 'OtherFruit2.webp', 'OtherFruit3.webp', 'OtherFruit4.webp', 'OtherFruit5.webp'],
      'Other Vegetables' => ['OtherVegetables1.webp', 'OtherVegetables2.webp', 'OtherVegetables3.webp', 'OtherVegetables4.webp', 'OtherVegetables5.webp'],
      'Potatoes, Yams & Tubers' => ['PotatoesYamsTubers1.webp', 'PotatoesYamsTubers2.webp', 'PotatoesYamsTubers3.webp', 'PotatoesYamsTubers4.webp', 'PotatoesYamsTubers5.webp'],
      'Root Vegetables' => ['RootVegetables1.webp', 'RootVegetables2.webp', 'RootVegetables3.webp', 'RootVegetables4.webp', 'RootVegetables5.webp'],
      'Stone Fruit' => ['StoneFruit1.webp', 'StoneFruit2.webp', 'StoneFruit3.webp', 'StoneFruit4.webp', 'StoneFruit5.webp'],
      'Tomatoes' => ['Tomatoes1.webp', 'Tomatoes2.webp', 'Tomatoes3.webp', 'Tomatoes4.webp', 'Tomatoes5.webp'],
      'Tropical Fruit' => ['TropicalFruit1.webp', 'TropicalFruit2.webp', 'TropicalFruit3.webp', 'TropicalFruit4.webp', 'TropicalFruit5.webp'],
      'Cucumbers' => ['Cucumbers1.webp', 'Cucumbers2.webp', 'Cucumbers3.webp', 'Cucumbers4.webp', 'Cucumbers5.webp'],
      'Eggplant & Squash' => ['EggplantSquash1.webp', 'EggplantSquash2.webp', 'EggplantSquash3.webp', 'EggplantSquash4.webp', 'EggplantSquash5.webp'],
      'Peppers' => ['Peppers1.webp', 'Peppers2.webp', 'Peppers3.webp', 'Peppers4.webp', 'Peppers5.webp'],
      'Goat Meat or Lamb' => ['Ground Goat', 'Lamb Chops'], */
      'Broccoli & Cauliflower' => ['Broccoli1.webp', 'Broccoli2.webp', 'Broccoli3.webp', 'Broccoli4.webp', 'Broccoli5.webp', 'Broccoli6.webp', 'Broccoli7.webp', 'Broccoli8.webp', 'Broccoli9.webp', 'Broccoli10.webp'],
    ];

    $mediaGalleryMap = [];

    foreach ($colorImagess as $color => $images) {
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
            $productName = "$grandTitle $p";
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
                        <p class="c--gry font18 m-0">Farm Fresh</p>
                    </div>
                    <div>
                        <h4 class="font18 c--blackc fw-medium m-0">Category</h4>
                        <p class="c--gry font18 m-0">' . $grandTitle . '</p>
                    </div>
                    <div>
                        <h4 class="font18 c--blackc fw-medium m-0">Type</h4>
                        <p class="c--gry font18 m-0">Fresh Produce</p>
                    </div>
                    <div>
                        <h4 class="font18 c--blackc fw-medium m-0">Origin</h4>
                        <p class="c--gry font18 m-0">Locally Sourced / Imported</p>
                    </div>
                    <div>
                        <h4 class="font18 c--blackc fw-medium m-0">Packaging</h4>
                        <p class="c--gry font18 m-0">Eco-friendly Pack</p>
                    </div>
                    <div>
                        <h4 class="font18 c--blackc fw-medium m-0">Shelf Life</h4>
                        <p class="c--gry font18 m-0">5–7 Days (Refrigerated)</p>
                    </div>
                    <div>
                        <h4 class="font18 c--blackc fw-medium m-0">Storage Instructions</h4>
                        <p class="c--gry font18 m-0">Store in a cool, dry place away from sunlight</p>
                    </div>
                    <div>
                        <h4 class="font18 c--blackc fw-medium m-0">Available Weight Options</h4>
                        <p class="c--gry font18 m-0">500g, 1kg, 2kg</p>
                    </div>
                    <div>
                        <h4 class="font18 c--blackc fw-medium m-0">Warranty</h4>
                        <p class="c--gry font18 m-0">Guaranteed Fresh on Delivery</p>
                    </div>
                    <div>
                        <h4 class="font18 c--blackc fw-medium m-0">SKU</h4>
                        <p class="c--gry font18 m-0">' . strtoupper(Str::slug($productName . '-' . substr(uniqid(), -4))) . '</p>
                    </div>
                </div>
            ',
              'specifications' => "Category: {$grandTitle}. Type: Fresh Produce. Origin: Locally Sourced / Imported. Packaging: Eco-friendly Pack. Shelf Life: 5–7 Days when refrigerated. Storage: Store in a cool, dry place away from sunlight. Weight Options: 500g, 1kg, 2kg.",
              'care_maintenance' => "Keep refrigerated after opening. Wash thoroughly before consumption. Avoid exposure to moisture or extreme heat.",
              'warranty' => "Guaranteed fresh on delivery. Replacement available for spoiled or damaged items reported within 24 hours.",
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

            foreach ($attributeMap['Weight'] as $weightName => $weightId) {
              $variantName = "$productName $weightName";

              $variant = ProductVariant::create([
                'product_id' => $product->id,
                'name' => $variantName,
                'sku' => strtoupper(Str::slug($variantName . '-' . substr(uniqid(), -4))),
                'regular_price' => $regularPrice = rand(300, 2000),
                'sale_price' => rand(200, $regularPrice - 1),
              ]);

              if (isset($mediaGalleryMap[$childTitle])) {
                $mediaIds = $mediaGalleryMap[$childTitle];
                $randomMediaId = $mediaIds[array_rand($mediaIds)];
                ProductVariantImages::create([
                  'product_variant_id' => $variant->id,
                  'media_gallery_id' => $randomMediaId,
                  'is_default' => 1
                ]);
              } else {
                ProductVariantImages::create([
                  'product_variant_id' => $variant->id,
                  'media_gallery_id' => range(1, 5)[array_rand(range(1, 5))],
                  'is_default' => 1
                ]);
              }

              /*  $mediaIds = $mediaGalleryMap[$childTitle];
              $randomMediaId = $mediaIds[array_rand($mediaIds)];

              ProductVariantImages::create([
                'product_variant_id' => $variant->id,
                'media_gallery_id' => $randomMediaId,
                'is_default' => 1
              ]);
 */
              ProductVariantAttribute::create([
                'product_variant_id' => $variant->id,
                'attribute_id' => $attributeMapIds['Weight'],
                'attribute_value_id' => $weightId,
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
