<?php

namespace App\Http\Controllers\Backend\ProductManage;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Http\Requests\Backend\ProductManage\ProductRequest;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductCategory;
use App\Services\Backend\Product\ProductService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Vinkla\Hashids\Facades\Hashids;

class ProductController extends Controller
{
  protected ProductService $productService;
  protected string $name = 'Products';
  protected $model = Product::class;

  public function __construct(protected CommonServiceInterface $commonService)
  {
    $this->productService = new ProductService($this->model, $this->name, 'product');
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  public function index(): View
  {
    return view('backend.pages.product-manage.products.index', ['cardHeader' => $this->name . ' List']);
  }

  public function create(): View
  {
    return view('backend.pages.product-manage.products.form', $this->productService->getCreateData());
  }

  public function edit(string $id = ''): View
  {
    return view('backend.pages.product-manage.products.form', $this->productService->getEditData($id));
  }

  public function store(ProductRequest $request): JsonResponse
  {
    return $this->model::store($request);
  }

  public function update(ProductRequest $request, string $id = ''): JsonResponse
  {
    return $this->commonService->update($request, $this->model, $id);
  }

  public function destroy(string $id): JsonResponse
  {
    return $this->commonService->destroy($this->model, $id);
  }

  public function toggle($id): JsonResponse
  {
    return $this->commonService->toggle($this->model, $id);
  }

  public function multiDestroy(BulkDestroyRequest $request): JsonResponse
  {
    return $this->commonService->multidestroy($request, $this->model);
  }
}
