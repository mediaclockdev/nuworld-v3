<?php

namespace App\Http\Controllers\Backend\ProductManage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Http\Requests\Backend\ProductManage\ProductRequest;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductCategory;
use App\Models\ProductReview;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Vinkla\Hashids\Facades\Hashids;

class ReviewController extends Controller
{
  protected string $name;
  protected $model;
  public function __construct()
  {
    $this->name = 'Product Reviews';
    $this->model = ProductReview::class;
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  public function index(): View
  {
    return view('backend.pages.product-manage.product-reviews.index', ['cardHeader' => $this->name . ' List']);
  }




  public function toggle($id): JsonResponse
  {
    return $this->model::toggleStatus($id);
  }
}
