<?php

namespace App\Http\Controllers\Backend\ProductManage;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Http\Requests\Backend\ProductManage\AttributeRequest;
use App\Models\ProductAttribute;
use App\Services\Backend\Product\AttributeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class AttributeController extends Controller
{
  protected AttributeService $attributeService;
  protected string $name = 'Product Attributes';
  protected $model = ProductAttribute::class;


  public function __construct(protected CommonServiceInterface $commonService)
  {
    $this->attributeService = new AttributeService($this->model, $this->name, 'productAttribute');
    view()->share('pageTitle', 'Manage ' . $this->name);
  }
  /**
   * Display a listing of the resource.
   */
  public function index(): View
  {
    return view('backend.pages.product-manage.attributes.index', ['cardHeader' => $this->name . ' List']);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): View
  {
    return view('backend.pages.product-manage.attributes.form', $this->attributeService->getCreateData());
  }

  public function edit($id = ''): View
  {

    return view('backend.pages.product-manage.attributes.form', $this->attributeService->getEditData($id));
  }

  public function store(AttributeRequest $request): JsonResponse
  {
    return $this->model::store($request);
  }

  public function update(AttributeRequest $request, $id = ''): JsonResponse
  {
    return $this->commonService->update($request, $this->model, $id);
  }

  public function destroy(int $id = 0): JsonResponse
  {
    return $this->commonService->destroy($this->model, $id);
  }

  public function multidestroy(BulkDestroyRequest $request): JsonResponse
  {
    return $this->commonService->multidestroy($request, $this->model);
  }

  public function toggle(int $id = 0): JsonResponse
  {
    return $this->commonService->toggle($this->model, $id);
  }
}
