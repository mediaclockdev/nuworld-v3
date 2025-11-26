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

  public function getCategoryBySlug($slug = null)
  {
    ifApiTokenExists();

    $category = $this->categoryService->getCategory($slug);

    if (!$category)
      return ApiResponse::error(__('response.not_found', ['item' => 'Category']), 404);

    if ($category->products->isEmpty()) {
      $productVariants = collect([]);
    } else {
      $productVariants = $category->products->map(function ($product) {
        return $product->variants()->first(); // Fetch first variant of each product
      })->filter();
    }

    return ApiResponse::success([
      'category' => CategoryResource::make($category),
      'product_variants' => ProductResource::collection($productVariants),
    ], __('response.success.fetch', ['item' => 'Category']));
  }
}
