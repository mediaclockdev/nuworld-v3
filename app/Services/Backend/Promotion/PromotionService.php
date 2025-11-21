<?php

namespace App\Services\Backend\Promotion;

use App\Models\Promotion;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductVariant;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\Backend\BaseFormService;

class PromotionService extends BaseFormService
{
  public function __construct(string $modelClass = Product::class, string $displayName = 'Product', string $variableName = 'product')
  {
    parent::__construct($modelClass, $displayName, $variableName);
  }
  /**
   * Prepare data for promotion create form.
   *
   * @return array
   */
  public function getCreateData(): array
  {
    $products = Product::where('status', 1)->get();
    $categories = ProductCategory::where('status', 1)->get();

    return [
      ...$this->getBaseCreateData(),
      'products' => $products,
      'categories' => $categories
    ];
  }

  /**
   * Prepare data for promotion edit form.
   *
   * @param string $id
   * @return array
   * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
   */
  public function getEditData(string $id): array
  {
    $promotion = $this->modelClass::findOrFail($id);
    $products = Product::where('status', 1)->get();
    $categories = ProductCategory::where('status', 1)->get();

    if (!$promotion) {
      abort(404, 'Promotion not found.');
    }

    return [
      ...$this->getBaseEditData($id),
      'products' => $products,
      'categories' => $categories
    ];
  }

  /**
   * Get product variants for a given product ID.
   *
   * @param Request $request
   * @return array
   */
  public function getProductVariants(Request $request): array
  {
    $hashid = $request->product_id;
    $id = Hashids::decode($hashid);

    if (empty($id)) {
      return ['success' => false, 'variants' => []];
    }

    $variants = ProductVariant::where('product_id', $id[0])
      ->where('status', 1)
      ->select('id', 'name')
      ->get();

    return ['success' => true, 'variants' => $variants];
  }
}
