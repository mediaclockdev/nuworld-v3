<?php

namespace App\Http\Controllers\Api\Frontend\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Frontend\BannerResource;
use App\Http\Resources\Api\Frontend\BestSellingProductResource;
use App\Http\Resources\Api\Frontend\BrandBannerResource;
use App\Http\Resources\Api\Frontend\CategoryCheckoutCollectionBannerResource;
use App\Http\Resources\Api\Frontend\CategoryHotDealsBannerResource;
use App\Http\Resources\Api\Frontend\CategoryResource;
use App\Http\Resources\Api\Frontend\ProductResource;
use App\Models\CustomBanner;
use App\Models\ProductVariant;
use App\Services\Frontend\BannerService;
use App\Services\Frontend\CategoryService;
use App\Services\Frontend\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryController extends Controller
{
  public function __construct(private CategoryService $categoryService, private BannerService $bannerService, private ProductService $productService) {}

  public function getCategories()
  {
    ifApiTokenExists();

    $categories = $this->categoryService->getNestedCategories(0, 7);

    return ApiResponse::success([
      'categories_nested' => CategoryResource::collection($categories),
    ], __('response.success.fetch', ['item' => 'Category Page Data']));
  }
  // public function getCategoryBySlug($slug = null)
  // {
  //   ifApiTokenExists();
  //   $category = $this->categoryService->getCategory($slug);
  //   //pd($category);

  //   if (!$category)
  //     return ApiResponse::error(__('response.not_found', ['item' => 'Category']), 404);

  //   $productVariants = $category->products->isNotEmpty()
  //     ? $this->productService->getProductVariants($category->products[0]->id)
  //     : collect([]);

  //   return ApiResponse::success([
  //     'category' => CategoryResource::make($category),
  //     'product_variants' => ProductResource::collection($productVariants),
  //   ], __('response.success.fetch', ['item' => 'Category']));
  // }

  // public function getCategoryBySlug($slug = null)
  // {
  //   ifApiTokenExists();

  //   $category = $this->categoryService->getCategory($slug);

  //   if (!$category)
  //     return ApiResponse::error(__('response.not_found', ['item' => 'Category']), 404);

  //   if ($category->products->isEmpty()) {
  //     $productVariants = collect([]);
  //   } else {
  //     $productVariants = $category->products->map(function ($product) {
  //       return $product->variants()->first(); // Fetch first variant of each product
  //     })->filter();
  //   }

  //   return ApiResponse::success([
  //     'category' => CategoryResource::make($category),
  //     'product_variants' => ProductResource::collection($productVariants),
  //   ], __('response.success.fetch', ['item' => 'Category']));
  // }

  public function getCategoryBySlug(Request $request, $slug = null)
  {
    ifApiTokenExists();

    $sort = $request->query('sort', 'relevance');

    $category = $this->categoryService->getCategory($slug);

    if (!$category) {
      return ApiResponse::error(__('response.not_found', ['item' => 'Category']), 404);
    }

    /**
     * -------------------------------------------------
     * STEP 1: Rank products in category (1st, 2nd)
     * -------------------------------------------------
     */
    $productRankSubQuery = DB::table('products')
      ->select(
        'id as product_id',
        DB::raw('ROW_NUMBER() OVER (ORDER BY id) as product_rank')
      )
      ->where('category_id', $category->id);

    /**
     * -------------------------------------------------
     * STEP 2: Pick variant based on product rank
     *   - product_rank = 1 → FIRST variant (MIN id)
     *   - product_rank = 2 → LAST variant (MAX id)
     * -------------------------------------------------
     */
    $variantIdSubQuery = DB::table('product_variants as pv')
      ->joinSub($productRankSubQuery, 'pr', function ($join) {
        $join->on('pr.product_id', '=', 'pv.product_id');
      })
      ->where('pv.status', 1)
      ->groupBy('pv.product_id', 'pr.product_rank')
      ->selectRaw("
            CASE
                WHEN pr.product_rank = 1 THEN MIN(pv.id)
                WHEN pr.product_rank = 2 THEN MAX(pv.id)
            END as id
        ");

    /**
     * -------------------------------------------------
     * STEP 3: Main query
     * -------------------------------------------------
     */
    $variantQuery = ProductVariant::query()
      ->with([
        'product',
        'product.category',
        'galleries',
        'inventory',
        'variantReviews'
      ])
      ->whereIn('id', $variantIdSubQuery);

    /**
     * -------------------------------------------------
     * STEP 4: Sorting
     * -------------------------------------------------
     */
    if ($sort === 'name-asc') {

      $variantQuery->orderBy('name', 'asc');
    } elseif ($sort === 'lowest-price') {

      $variantQuery->orderByRaw('COALESCE(sale_price, regular_price) asc');
    } elseif ($sort === 'highest-price') {

      $variantQuery->orderByRaw('COALESCE(sale_price, regular_price) desc');
    } elseif ($sort === 'top-rated') {

      $variantQuery->leftJoinSub(
        DB::table('product_reviews')
          ->select(
            'variant_id',
            DB::raw('AVG(rating) as avg_rating'),
            DB::raw('COUNT(*) as review_count')
          )
          ->groupBy('variant_id'),
        'rv',
        'rv.variant_id',
        '=',
        'product_variants.id'
      )
        ->select(
          'product_variants.*',
          DB::raw('COALESCE(rv.avg_rating, 0) as avg_rating'),
          DB::raw('COALESCE(rv.review_count, 0) as review_count')
        )
        ->orderByDesc('avg_rating')
        ->orderByDesc('review_count')
        ->orderByDesc('product_variants.created_at');
    } else {
      // relevance / most-recent
      $variantQuery->orderByDesc('product_variants.created_at');
    }

    /**
     * -------------------------------------------------
     * STEP 5: Execute
     * -------------------------------------------------
     */
    $productVariants = $variantQuery->get();

    $banner = CustomBanner::where('position', 'category_page_banner')->first();

    return ApiResponse::success([
      'category'         => CategoryResource::make($category),
      'product_variants' => ProductResource::collection($productVariants),
      'categoryBanner'   => BannerResource::make($banner),
      'applied_sort'     => $sort
    ], __('response.success.fetch', ['item' => 'Category']));
  }
}
