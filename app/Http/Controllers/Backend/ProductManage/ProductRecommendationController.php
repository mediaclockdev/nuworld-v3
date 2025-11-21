<?php

namespace App\Http\Controllers\Backend\ProductManage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Http\Requests\Backend\ProductManage\BestSellerRequest;
use App\Http\Requests\Backend\Promotions\PromotionRequest;
use App\Models\BestSeller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductRecommendation;
use App\Models\ProductVariant;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Http\Request;

class ProductRecommendationController extends Controller
{
  public function __construct()
  {
    view()->share('pageTitle', 'Manage Product Recommendation');
  }

  /**
   * Display the list of coupons.
   */
  public function index(): View
  {
    return view('backend.pages.product-manage.product-recommendation.index', [
      'cardHeader' => 'Product Recommendation List',
    ]);
  }

  /**
   * Show the form for creating a new coupon.
   */
  public function create(): View
  {
    $products = Product::where('status', 1)->get();
    $categories = ProductCategory::where('status', 1)->get();
    return view('backend.pages.product-manage.product-recommendation.form', [
      'cardHeader' => 'Create Product Recommendation',
      'base_seller' => new ProductRecommendation(),
      'products' => $products,
      'categories' => $categories
    ]);
  }

  /**
   * Show the form for editing an existing coupon.
   */
  public function edit(string $id): View
  {
    $base_seller = BestSeller::find($id);
    $products = Product::where('status', 1)->get();
    $categories = ProductCategory::where('status', 1)->get();

    if (!$base_seller) {
      abort(404, 'Best Seller not found.');
    }

    return view('backend.pages.product-manage.best-sellers.form', [
      'cardHeader' => 'Edit Best Seller',
      'base_seller' => $base_seller,
      'products' => $products,
      'categories' => $categories
    ]);
  }

  /**
   * Store a newly created coupon.
   */
  public function store(BestSellerRequest $request): JsonResponse
  {
    return BestSeller::store($request);
  }

  /**
   * Update an existing coupon.
   */
  public function update(BestSellerRequest $request, string $id): JsonResponse
  {
    //pd($request->all());
    return BestSeller::store($request, $id);
  }



  public function getProductVariants(Request $request)
  {
    //dd($request->product_id);
    $hashid = $request->product_id;
    $id = Hashids::decode($hashid);

    if (empty($id)) {
      return response()->json(['success' => false, 'variants' => []]);
    }

    $variants = ProductVariant::where('product_id', $id[0])
      ->where('status', 1)
      ->select('id', 'name') // change fields as needed
      ->get();

    return response()->json(['success' => true, 'variants' => $variants]);
  }


  /**
   * Bulk delete coupons.
   */
  public function multiDestroy(BulkDestroyRequest $request): JsonResponse
  {
    $decodedIds = $request->decodedIds();
    $failed = [];

    foreach ($decodedIds as $id) {
      $result = Coupon::remove($id)->getData(true);

      if (!($result['success'] ?? false)) {
        return response()->json($result);
      }
    }

    return response()->json([
      'success' => true,
      'message' => __('response.success.delete', ['item' => 'Coupon(s)']),
    ]);
  }
}
