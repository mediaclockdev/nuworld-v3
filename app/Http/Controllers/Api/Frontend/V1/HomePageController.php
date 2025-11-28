<?php

namespace App\Http\Controllers\Api\Frontend\V1;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Http\Resources\Api\Frontend\LandingPageResource;
use App\Models\BestSeller;
use App\Models\ProductCategory;
use App\Models\ProductRecommendation;
use App\Models\SiteSetting;
use App\Services\Frontend\{
  BannerService,
  CategoryService,
  HomePageService,
  CartService,
  ProductService
};
use Illuminate\Http\Request;

class HomePageController extends Controller
{
  public function __construct(private CategoryService $categoryService, private BannerService $bannerService, private HomePageService $homePageService, private CartService $cartService, protected ProductService $productService) {}
  public function index()
  {
    ifApiTokenExists();
    $siteName = SiteSetting::where('key', 'sitename')->value('value');
    $cart_items_data = $this->cartService->getCartData();
    $cartItems = collect($cart_items_data['cart_items'] ?? []);
    $cart_items_data = $this->cartService->getCartData();
    $savedItems = collect($cart_items_data['saved_for_later_items'] ?? []);
    $recommended_products = BestSeller::with('variant')->get();

    $variantList = $recommended_products->pluck('variant')->filter();
    // pd($recommended_products);

    // For Top Four Categories
    $homePageTopCategoryBanner = $this->bannerService->getBanner('home_page_top_category_banner', false, 'custom_order')->first();
    $topFourProductCategoriesData = [];

    if ($homePageTopCategoryBanner && $homePageTopCategoryBanner->settings) {
      $homePageTopCategoryBannerArray = json_decode($homePageTopCategoryBanner->settings, true);
      if (!empty($homePageTopCategoryBannerArray['options'])) {
        $topFourProductCategories = ProductCategory::whereHas('products')
          ->whereIn('id', $homePageTopCategoryBannerArray['options'])
          ->get();
        if ($topFourProductCategories->count() > 0) {
          $topFourProductCategoriesData = $topFourProductCategories;
        }
      }
    }
    if (empty($topFourProductCategoriesData)) {
      $topFourProductCategoriesData = ProductCategory::whereHas('products')->take(4)->get();
    }


    $data = [
      'site_logo' => siteLogo(),
      'site_name' => $siteName ?? 'NEUWRLD',
      'cart_count' => count($cartItems),
      'wishlist_count' => count($savedItems),
      'productCategories' => $topFourProductCategoriesData,
      'all_categories' => $this->categoryService->getCategoriesWithProducts(0, 'latest'),
      'home_banner' => $this->bannerService->getBanner('hero', false, 'custom_order'),
      'home_inner_banner' => $this->bannerService->getBanner('app_home_landing_inner_banner', false, 'custom_order'),
      'latest_products' => $variantList,
      'footer_menu' => $this->homePageService->getAppFooterMenus()
    ];
    return ApiResponse::success(new LandingPageResource($data), __('response.success.fetch', ['item' => 'Home Landing Page Data']));
  }


  public function blogs(Request $request)
  {
    return $this->fetchBlogs($request, 'min');
  }


  public function blogList(Request $request)
  {
    return $this->fetchBlogs($request, 'all');
  }

  private function fetchBlogs(Request $request, string $listType = 'all')
  {
    $data = $this->homePageService->getBlogs($request, $listType);

    return ApiResponse::successWithPagination(
      $data['blogs'],
      $data['pagination'],
      __('response.success.fetch', ['item' => 'Blogs'])
    );
  }

  public function limitedTimeDeal(Request $request)
  {
    $data = $this->homePageService->getLimitedTimeDeals($request->all());

    return ApiResponse::successWithPagination(
      $data['deals'],
      $data['pagination'],
      __('response.success.fetch', ['item' => 'Deals'])
    );
  }

  public function footerMenus()
  {
    return ApiResponse::success($this->homePageService->getAppFooterMenus(), __('response.success.fetch', ['item' => 'Footer Menus']));
  }
}
