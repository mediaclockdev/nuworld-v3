<?php

namespace App\Services\Backend\InventoryManage;

use App\Http\Requests\Backend\InventoryManage\InventoryRequest;
use Illuminate\Http\JsonResponse;

class InventoryService
{
  public function __construct(
    protected $model,
    protected string $name,
    protected string $routePrefix
  ) {}

  public function getEditData(string $id): array
  {
    $inventoryValue = $this->model::with('product', 'variant')->find($id);

    if (!$inventoryValue) {
      abort(404);
    }

    return [
      'cardHeader' => 'Edit ' . $this->name,
      'inventoryValue' => $inventoryValue
    ];
  }

  public function updateInventory(InventoryRequest $request, string $id): JsonResponse
  {
    $inventory = $this->model::find($id);

    if (!$inventory) {
      return response()->json([
        'success' => false,
        'message' => __('response.not_found', ['item' => 'Inventory'])
      ]);
    }

    $productID = $inventory->product_id;
    $variantID = $inventory->product_variant_id;

    $newId = $this->model::store($request, $productID, $variantID);

    if (!$newId) {
      return response()->json([
        'success' => false,
        'message' => __('response.error.update', ['item' => $this->name])
      ]);
    }

    return response()->json([
      'success' => true,
      'message' => __('response.success.update', ['item' => $this->name])
    ]);
  }
}
