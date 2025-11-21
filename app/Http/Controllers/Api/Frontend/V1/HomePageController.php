<?php

namespace App\Http\Controllers\Api\Frontend\V1;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Http\Resources\Api\Frontend\LandingPageResource;
use App\Models\SiteSetting;
use App\Services\Frontend\{
  BannerService,
  CategoryService,
  HomePageService,
  CartService
};
use Illuminate\Http\Request;

class HomePageController extends Controller
{
  public function __construct(private CategoryService $categoryService, private BannerService $bannerService, private HomePageService $homePageService, private CartService $cartService) {}
  public function index()
  {
    ifApiTokenExists();
    $siteName = SiteSetting::where('key', 'sitename')->value('value');
    $cart_items_data = $this->cartService->getCartData();
    $cartItems = collect($cart_items_data['cart_items'] ?? []);
    $cart_items_data = $this->cartService->getCartData();
    $savedItems = collect($cart_items_data['saved_for_later_items'] ?? []);

    $data = [
      'site_logo' => siteLogo(),
      'site_name' => $siteName ?? 'Sundew Ecomm',
      'cart_count' => count($cartItems),
      'wishlist_count' => count($savedItems),
      'productCategories' => $this->categoryService->getCategoriesWithProducts(6, 'latest'),
      'all_categories' => $this->categoryService->getCategoriesWithProducts(0, 'latest'),
      'home_banner' => $this->bannerService->getBanner('hero', false, 'custom_order'),
      'home_inner_banner' => $this->bannerService->getBanner('app_home_landing_inner_banner', false, 'custom_order'),
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
