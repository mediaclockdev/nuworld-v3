<?php

namespace App\Services\Backend\Product;

use App\Http\Requests\Backend\ProductManage\VariationRequest;
use App\Http\Requests\Backend\ProductManage\VariationRequestSingle;
use App\Http\Resources\VariantDetails;
use App\Models\Inventory;
use App\Models\MediaGallery;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductFilter;
use App\Models\ProductVariant;
use App\Models\ProductVariantImages;
use App\Services\Backend\BaseFormService;
use App\Services\ImageUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Vinkla\Hashids\Facades\Hashids;

class ProductVariationService extends BaseFormService
{
  protected ImageUploadService $imageUploadService;

  public function __construct(
    ImageUploadService $imageUploadService,
    string $modelClass = ProductVariant::class,
    string $displayName = 'Product Variations',
    string $variableName = 'productVariation'
  ) {
    parent::__construct($modelClass, $displayName, $variableName);
    $this->imageUploadService = $imageUploadService;
  }

  /**
   * Prepare data for product variation create form.
   *
   * @return array
   */
  public function getCreateData(): array
  {
    $products = Product::where('status', 1)->get();
    $attributes = ProductAttribute::where('status', 1)->get();
    $attributeValues = ProductAttributeValue::where('status', 1)->get();

    return [
      ...$this->getBaseCreateData(),
      'products' => $products,
      'attributes' => $attributes,
      'attributeValues' => $attributeValues,
    ];
  }

  /**
   * Prepare data for product variation edit form.
   *
   * @param string $id
   * @return JsonResponse
   */
  public function getEditData(string $id): JsonResponse
  {
    $productVariation = $this->modelClass::with(['inventory', 'images.gallery'])->find($id);
    if (!$productVariation) {
      return response()->json(['success' => false, 'message' => 'Product Variation not found']);
    }

    $data = VariantDetails::make($productVariation);

    return response()->json(['success' => true, 'data' => $data]);
  }

  /**
   * Get variations for a specific product.
   *
   * @param string $id
   * @return JsonResponse
   */
  public function getVariationsByProduct(string $id): JsonResponse
  {
    $productVariations = $this->modelClass::where('product_id', $id)->get(['id', 'name', 'sku']);
    $productVariations = $productVariations->map(function ($productVariation) {
      return [
        'id' => Hashids::encode($productVariation->id),
        'name' => $productVariation->name,
        'variant_sku' => $productVariation->sku
      ];
    });

    return response()->json(['success' => true, 'data' => $productVariations]);
  }

  /**
   * Store multiple product variations.
   *
   * @param VariationRequest $request
   * @return JsonResponse
   */
  public function store(VariationRequest $request): JsonResponse
  {
    $product = Product::find($request->product_id);
    if (!$product) {
      return response()->json(['success' => false, 'message' => 'Product not found']);
    }

    $variants = [];
    foreach ($request->variations as $variation) {
      $nameParts = [];
      $attributes = [];

      foreach ($variation as $value) {
        $nameParts[] = $value['label'];
        $attributes[$value['attribute_id']] = $value['value_id'];
      }

      $variants[] = [
        'product_id' => $product->id,
        'name' => $product->name . ' ' . implode(' ', $nameParts),
        'sku' => $product->sku . '-' . strtoupper(implode('-', $nameParts)),
        'regular_price' => 0,
        'attributes' => $attributes,
        'created_by' => user('admin')->id,
        'updated_by' => user('admin')->id
      ];
    }

    if ($request->has('attribute_ids') && !empty($request->attribute_ids)) {
      $attributeIds = $request->attribute_ids;

      foreach ($attributeIds as $attributeId) {
        ProductFilter::create([
          'product_id' => $product->id,
          'attribute_id' => $attributeId,
        ]);
      }
    }


    return $this->modelClass::store($variants);
  }

  /**
   * Update a single product variation.
   *
   * @param VariationRequestSingle $request
   * @param string $id
   * @return JsonResponse
   */
  public function update(VariationRequestSingle $request, string $id): JsonResponse
  {
    $variant = $this->modelClass::storeSingle($request, $id);

    if (!$variant) {
      return response()->json(['success' => false, 'message' => 'Product Variation not found']);
    }

    $variantID = $variant->id;
    $productID = $variant->product_id;

    $inventoryId = Inventory::store($request, $productID, $variantID);

    if (!$inventoryId) {
      return response()->json(['success' => false, 'message' => 'Inventory not found']);
    }

    if ($request->has('product_images')) {
      $directory = 'media/products';
      $files = $request->file('product_images');

      foreach ($files as $key => $file) {
        $isDefault = false;
        if ($key == $request->default_image_index) {
          $isDefault = true;
        }
        $extension = $file->getClientOriginalExtension();
        $filename = uniqid() . '.' . $extension;
        $mimeType = $file->getMimeType();

        if (str_starts_with($mimeType, 'image')) {
          $upload = $this->imageUploadService->uploadImage($file, $directory, 'images', true);
        } else {
          $subDirectory = str_starts_with($mimeType, 'video') ? 'videos' : 'files';
          $upload = Storage::disk('public')->putFileAs("/uploads/{$directory}/{$subDirectory}", $file, $filename);
          $upload = ['filename' => $filename];
        }

        $title = '';

        $imageID = MediaGallery::store($title, $upload['filename'], $mimeType);

        ProductVariantImages::store($variantID, $imageID, $isDefault);
      }
    }

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Product Variations'])]);
  }

  /**
   * Set default image for a product variation.
   *
   * @param string $id
   * @param Request $request
   * @return JsonResponse
   */
  public function setDefaultImage(string $id, Request $request): JsonResponse
  {
    return ProductVariantImages::setDefaultImage($id, $request->image_id);
  }

  /**
   * Delete an image for a product variation.
   *
   * @param string $id
   * @return JsonResponse
   */
  public function deleteImage(string $id): JsonResponse
  {
    return ProductVariantImages::remove($id);
  }
}
