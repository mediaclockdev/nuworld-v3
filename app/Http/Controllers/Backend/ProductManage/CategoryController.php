<?php

namespace App\Http\Controllers\Backend\ProductManage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Http\Requests\Backend\ProductManage\CategoryRequest;
use App\Models\ProductCategory;
use App\Services\ImageUploadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;


class CategoryController extends Controller
{
  protected $imageUploadService;
  protected string $name;
  protected $model;
  public function __construct(ImageUploadService $imageUploadService)
  {
    $this->name = 'Product Category';
    $this->model = ProductCategory::class;
    $this->imageUploadService = $imageUploadService;
    view()->share('pageTitle', 'Manage ' . $this->name);
  }
  /**
   * Display a listing of the resource.
   */
  public function index(): View
  {
    return view('backend.pages.product-manage.category.index', ['cardHeader' => $this->name . ' List']);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): View
  {
    $Category = $this->model::generateTree('active');

    return view('backend.pages.product-manage.category.form', ['cardHeader' => 'Create ' . $this->name, 'categories' => $Category, 'productCategory' => new $this->model()]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(CategoryRequest $request): JsonResponse
  {

    if ($request->hasFile('category_image')) {
      $image = $request->file('category_image');
      $directory = 'categories';
      $imageType = '';

      $upload = $this->imageUploadService->uploadImage($image, $directory, $imageType, true);
      if ($upload['filename'] == '')
        return response()->json(['success' => false, 'message' => 'Failed to upload image']);

      $request->merge(['image_name' => $upload['filename']]);
    }
    return $this->model::store($request);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  string  $id
   * @return \Illuminate\Contracts\View\View
   */
  public function edit(string $id = ''): View
  {
    $productCategory = $this->model::find($id);

    if (!$productCategory)
      abort(404);

    $Categories = $this->model::generateTree('active');

    return view('backend.pages.product-manage.category.form', ['cardHeader' => 'Edit ' . $this->name, 'productCategory' => $productCategory, 'categories' => $Categories]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(CategoryRequest $request, string $id = ''): JsonResponse
  {
    $category = $this->model::find($id);
    if (!$category)
      return abort(404);

    if ($request->hasFile('category_image')) {
      $image = $request->file('category_image');
      $directory = 'categories';
      $imageType = '';
      // Delete the old image file
      if (Storage::disk('public')->exists('uploads/categories/' . $category->category_image)) {
        Storage::disk('public')->delete('uploads/categories/' . $category->category_image);
        Storage::disk('public')->delete('uploads/categories/thumbnail/' . $category->category_image);
      }

      $upload = $this->imageUploadService->uploadImage($image, $directory, $imageType, true);
      if ($upload['filename'] == '')
        return response()->json(['success' => false, 'message' => 'Failed to upload image']);

      $request->merge(['image_name' => $upload['filename']]);
    } else
      $request->merge(['image_name' => $this->model::find($id)->category_image]);
    return $this->model::store($request, $id);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id): JsonResponse
  {
    return $this->model::remove($id);
  }

  /**
   * Toggle the status of a product category.
   *
   * @param string $id
   * @return JsonResponse
   */
  public function toggle($id): JsonResponse
  {
    return $this->model::toggleStatus($id);
  }

  /**
   * Destroy multiple product categories
   *
   * @param BulkDestroyRequest $request
   * @return JsonResponse
   */
  public function multiDestroy(BulkDestroyRequest $request): JsonResponse
  {
    $decodedIds = $request->decodedIds(); // Already validated

    foreach ($decodedIds as $id) {
      $result = $this->model::remove($id)->getData(true);
      if ($result["success"] === false) {
        return response()->json($result);
      }
    }

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Product Categorie(s)'])]);
  }
}
