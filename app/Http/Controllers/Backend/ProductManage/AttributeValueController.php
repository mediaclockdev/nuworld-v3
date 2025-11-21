<?php

namespace App\Http\Controllers\Backend\ProductManage;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Http\Requests\Backend\ProductManage\AttributeValueRequest;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Services\Backend\Product\AttributeValueService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class AttributeValueController extends Controller
{
  protected AttributeValueService $attributeValueService;
  protected string $name = 'Product Attribute Values';
  protected $model = ProductAttributeValue::class;

  public function __construct(protected CommonServiceInterface $commonService)
  {
    $this->attributeValueService = new AttributeValueService($this->model, $this->name, 'attributeValue');
    view()->share('pageTitle', 'Manage ' . $this->name);
  }
  /**
   * Display a listing of the resource.
   */
  public function index(): View
  {
    return view('backend.pages.product-manage.attribute-values.index', ['cardHeader' => $this->name . ' List']);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): View
  {

    return view('backend.pages.product-manage.attribute-values.form', $this->attributeValueService->getCreateData());
  }

  public function edit($id = 0): View
  {
    return view('backend.pages.product-manage.attribute-values.form', $this->attributeValueService->getEditData($id));
  }

  public function store(AttributeValueRequest $request): JsonResponse
  {
    return $this->model::store($request);
  }

  public function update(AttributeValueRequest $request, $id = ''): JsonResponse
  {
    return $this->commonService->update($request, $this->model, $id);
  }

  public function destroy(int $id = 0): JsonResponse
  {
    return $this->commonService->destroy($this->model, $id);
  }

  public function toggle(int $id = 0): JsonResponse
  {
    return $this->commonService->toggle($this->model, $id);
  }

  public function multiDestroy(BulkDestroyRequest $request): JsonResponse
  {
    $decodedIds = $request->decodedIds(); // Already validated

    $failed = [];
    foreach ($decodedIds as $id) {
      $result = $this->model::remove($id)->getData(true);
      if ($result["success"] === false) {
        return response()->json($result);
      }
    }

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Product Attribute Value(s)'])]);
  }
}
