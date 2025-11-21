<?php

namespace App\Services\Frontend;

use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\ProductCategory;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Vinkla\Hashids\Facades\Hashids;

class ProductCardService
{
  /**
   * Fetch products with their variants, grouped by non-color attributes.
   *
   * @param Request $request
   * @param array $productIds
   * @param string|null $variantName
   * @return Collection
   */
  public $colorNames = ['color', 'colour', 'Colour', 'Color'];
 public function getProductsWithVariants(Request $request, array $productIds = [], ?string $variantName = null): Collection
{
    if($request->category_slug!=='all'){
        $categories = [$request->category_slug];
    }
    else{
        $categories = ['red-apples', 'hass-avocados', 'chicken', 'white-bread'];
    }
    $products = collect();
    foreach ($categories as $slug) {
        $categoryId = ProductCategory::where('slug', $slug)->value('id');

        if (!$categoryId) {
            continue;
        }

        $count = rand(1, 3); // 🔥 random count per category (1–3 products)

        $query = Product::with([
            'variants' => function ($query) use ($variantName) {
                $query->with([
                    'images' => function ($q) {
                        $q->where('is_default', 1)->with('gallery');
                    },
                    'variantAttributes.attribute',
                    'variantAttributes.attributeValue',
                ]);

                if ($variantName) {
                    $query->orderByRaw('CASE WHEN name = ? OR sku = ? THEN 0 ELSE 1 END', [$variantName, $variantName]);
                }
            },
        ])
        ->where('status', 1)
        ->where('category_id', $categoryId)
        ->when(!empty($productIds), fn($q) => $q->whereIn('id', $productIds))
        ->inRandomOrder()
        ->take($count)
        ->get();

        $products = $products->merge($query);
    }

    $products = $products->shuffle();

    foreach ($products as $product) {
        if ($request->filled('excludeProductId')) {
            $excludeId = Hashids::decode($request->excludeProductId)[0];
            $product->variants = $this->groupVariants(
                $product->variants->where('product_id', '!=', $excludeId)
            );
        } else {
            $product->variants = $this->groupVariants($product->variants);
        }
    }

    return $products->values();
}


  /**
   * Group variants by non-color attributes and add colorOptions.
   *
   * @param Collection $products
   * @return Collection
   */
  public function groupVariants(Collection $variants): Collection
  {
    $groupedVariants = collect();

    $variantsByKey = $variants->groupBy(function ($variant) {
      return $this->generateVariantKey($variant);
    });

    foreach ($variantsByKey as $key => $variantGroup) {
      $representativeVariant = $variantGroup->first();

      $representativeVariant->colorOptions = $variantGroup->map(function ($variant) {
        $colorAttribute = $variant->variantAttributes->first(function ($attr) {
          return ($attr->attribute_id === 1 && isset($attr->attribute['name']) && in_array($attr->attribute['name'], $this->colorNames));
        });
        return (object) [
          'variant' => $variant,
          'attributeValue' => $colorAttribute ? $colorAttribute->attributeValue : null,
        ];
      })->filter(function ($option) {
        return $option->attributeValue !== null;
      });

      $groupedVariants->push($representativeVariant);
    }
    return $groupedVariants;
  }


  /**
   * Generate a unique key for grouping variants by non-color attributes.
   *
   * @param \App\Models\ProductVariant $variant
   * @return string
   */
  protected function generateVariantKey($variant): string
  {
    $attributes = $variant->variantAttributes
      ->where('attribute_id', '!=', 1) // Exclude color attribute
      ->pluck('attributeValue.value')
      ->sort()
      ->implode('-');
    return $attributes ?: $variant->id;
  }

  public function getVariantDetails(int $variantId, ?string $color = null): array
  {
    $variant = ProductVariant::with(['variantAttributes.attribute', 'variantAttributes.attributeValue', 'images.gallery'])
      ->find($variantId);

    if (!$variant) {
      return ['success' => false];
    }

    if ($color && $colorAttributeValue = ProductAttributeValue::where('value', $color)->first()) {
      $otherAttributes = $variant->variantAttributes
        ->where('attribute_id', '!=', 1)
        ->pluck('attribute_value_id', 'attribute_id')
        ->all();

      $variant = ProductVariant::where('product_id', $variant->product_id)
        ->whereHas(
          'variantAttributes',
          fn($query) => $query
            ->where('attribute_id', 1)
            ->where('attribute_value_id', $colorAttributeValue->id)
        )
        ->where(function ($query) use ($otherAttributes) {
          foreach ($otherAttributes as $attrId => $valueId) {
            $query->whereHas(
              'variantAttributes',
              fn($q) => $q
                ->where('attribute_id', $attrId)
                ->where('attribute_value_id', $valueId)
            );
          }
        })
        ->with(['variantAttributes.attribute', 'variantAttributes.attributeValue', 'images.gallery'])
        ->first() ?? $variant;
    }

    $promo = findSalePrice($variant->id);
    $defaultImage = $variant->images->firstWhere('is_default', 1);
    $imagePath = $defaultImage
      ? asset("public/storage/uploads/media/products/images/{$defaultImage->gallery->file_name}")
      : asset('public/backend/assetss/images/products/product_thumb.jpg');
      // Add special price logic
    $specialPrice = !empty($promo['promotion_id']) && $promo['promotion_id'] ? true : false;

    return [
      'success' => true,
      'variant' => [
        'name' => $variant->name,
        'value' => Hashids::encode($variant->id),
        'regular_price' => displayPrice($promo['regular_price']),
        'sale_price' => displayPrice($promo['display_price']),
        'isDiscount' => !$promo['regular_price_true'],
        'discount_percent' => $promo['display_discount'],
        'sku' => $variant->sku,
        'in_cart' => isInCart($variant->id),
        'url' => route('product.show', $variant->sku),
        'image_name' => $defaultImage?->gallery->file_name ?? 'Product image',
        'image' => $imagePath,
        'isOutOfStock' => $variant->inventory?->quantity < 1,
        'special_price' => $specialPrice, // Add special price flag
      ],
    ];
  }
}
