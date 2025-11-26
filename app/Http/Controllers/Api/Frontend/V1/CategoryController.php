<?php

namespace App\Http\Controllers\Api\Frontend\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Frontend\BestSellingProductResource;
use App\Http\Resources\Api\Frontend\BrandBannerResource;
use App\Http\Resources\Api\Frontend\CategoryCheckoutCollectionBannerResource;
use App\Http\Resources\Api\Frontend\CategoryHotDealsBannerResource;
use App\Http\Resources\Api\Frontend\CategoryResource;
use App\Http\Resources\Api\Frontend\ProductResource;
use App\Services\Frontend\BannerService;
use App\Services\Frontend\CategoryService;
use App\Services\Frontend\ProductService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
  public function __construct(private CategoryService $categoryService, private BannerService $bannerService, private ProductService $productService) {}

  public function getCategories()
  {
    ifApiTokenExists();

    $categories = $this->categoryService->getCategoriesWithProducts(12);
    // $dealsOfTheDay = $this->bannerService->getBanner('hot_deals_category_banner', false, 'custom_order');
    // $brands = $this->bannerService->getBanner('brand_carousel', false, 'custom_order')->take(10);
    // $best_selling_products = $this->productService->getBestSellingProducts();
    $categoriesNested = $this->categoryService->getNestedCategories();
    pd($categoriesNested);
    //$checkout_collections = $this->bannerService->getBanner('app_category_page_checkout_collections', false, 'custom_order');

    return ApiResponse::success([
      'category_grid_view' => CategoryResource::collection($categories),
      // 'deals_of_the_day' => CategoryHotDealsBannerResource::collection($dealsOfTheDay),
      // 'brands' => BrandBannerResource::collection($brands),
      // 'best_selling_products' => BestSellingProductResource::collection($best_selling_products),
      'categories_nested' => CategoryResource::collection($categoriesNested),
      //'checkout_collections' => CategoryCheckoutCollectionBannerResource::collection($checkout_collections),
    ], __('response.success.fetch', ['item' => 'Category Page Data']));
  }

  public function getCategoryBySlug($slug = null)
  {
    ifApiTokenExists();
    $category = $this->categoryService->getCategory($slug);

    if (!$category)
      return ApiResponse::error(__('response.not_found', ['item' => 'Category']), 404);

    $productVariants = $category->products->isNotEmpty()
      ? $this->productService->getProductVariants($category->products[0]->id)
      : collect([]);

    return ApiResponse::success([
      'category' => CategoryResource::make($category),
      'product_variants' => ProductResource::collection($productVariants),
    ], __('response.success.fetch', ['item' => 'Category']));
  }
}
