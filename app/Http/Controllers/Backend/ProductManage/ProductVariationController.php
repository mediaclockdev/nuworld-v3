<?php

namespace App\Http\Controllers\Backend\ProductManage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Http\Requests\Backend\ProductManage\VariationRequest;
use App\Http\Requests\Backend\ProductManage\VariationRequestSingle;
use App\Models\ProductVariant;
use App\Contracts\CommonServiceInterface;
use App\Services\Backend\Product\ProductVariationService;
use App\Services\ImageUploadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductVariationController extends Controller
{
  protected string $name = 'Product Variations';
  protected ProductVariationService $productVariationService;
  protected $model = ProductVariant::class;

  public function __construct(
    protected CommonServiceInterface $commonService,
    ImageUploadService $imageUploadService
  ) {
    $this->productVariationService = new ProductVariationService($imageUploadService, $this->model, $this->name, 'productVariation');
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  public function index(): View
  {
    return view('backend.pages.product-manage.product-variations.index', ['cardHeader' => $this->name . ' List']);
  }

  public function variationsByProduct(string $id = ''): JsonResponse
  {
    return $this->productVariationService->getVariationsByProduct($id);
  }

  public function create(): View
  {
    abort(404);
    return view('backend.pages.product-manage.product-variations.form', $this->productVariationService->getCreateData());
  }

  public function store(VariationRequest $request): JsonResponse
  {
    return $this->productVariationService->store($request);
  }

  public function edit(string $id = ''): JsonResponse
  {
    return $this->productVariationService->getEditData($id);
  }

  public function update(VariationRequestSingle $request, string $id = ''): JsonResponse
  {
    return $this->productVariationService->update($request, $id);
  }

  public function setDefaultImage(string $id = '', Request $request): JsonResponse
  {
    return $this->productVariationService->setDefaultImage($id, $request);
  }

  public function deleteImage(string $id = ''): JsonResponse
  {
    return $this->productVariationService->deleteImage($id);
  }

  public function destroy(string $id): JsonResponse
  {
    return $this->commonService->destroy($this->model, $id);
  }

  public function multiDestroy(BulkDestroyRequest $request): JsonResponse
  {
    return $this->commonService->multiDestroy($request, $this->model);
  }

  public function toggle(string $id): JsonResponse
  {
    return $this->commonService->toggle($this->model, $id);
  }
}
