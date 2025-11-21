<?php

namespace App\Services\Backend\Product;

use App\Http\Requests\Backend\ProductManage\ProductRequest;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductCategory;
use App\Services\Backend\BaseFormService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;

class ProductService extends BaseFormService
{
  public function __construct(string $modelClass = Product::class, string $displayName = 'Product', string $variableName = 'product')
  {
    parent::__construct($modelClass, $displayName, $variableName);
  }

  /**
   * Prepare data for product create form.
   *
   * @return array
   */
  public function getCreateData(): array
  {
    $categories = ProductCategory::generateTree('active');
    $attributes = ProductAttribute::where('status', 1)->get();
    $attributeValues = ProductAttributeValue::where('status', 1)
      ->get(['id', 'attribute_id', 'value', 'value_details', 'sequence'])
      ->map(function ($value) {
        $value->encoded_id = Hashids::encode($value->id);
        $value->attribute_id = Hashids::encode($value->attribute_id) ?? $value->attribute_id;
        return $value;
      });

    return [
      ...$this->getBaseCreateData(),
      'categories' => $categories,
      'attributes' => $attributes,
      'attributeValues' => $attributeValues,
    ];
  }

  /**
   * Prepare data for product edit form.
   *
   * @param string $id
   * @return array
   */
  public function getEditData(string $id): array
  {
    $categories = ProductCategory::generateTree('active');
    $attributes = ProductAttribute::where('status', 1)->get();
    $attributeValues = ProductAttributeValue::where('status', 1)
      ->get(['id', 'attribute_id', 'value', 'value_details', 'sequence'])
      ->map(function ($value) {
        $value->encoded_id = Hashids::encode($value->id);
        $value->attribute_id = Hashids::encode($value->attribute_id) ?? $value->attribute_id;
        return $value;
      });

    return [
      ...$this->getBaseEditData($id),
      'categories' => $categories,
      'attributes' => $attributes,
      'attributeValues' => $attributeValues,
    ];
  }
}
