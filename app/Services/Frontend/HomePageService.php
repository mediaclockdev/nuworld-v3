<?php

namespace App\Services\Frontend;

use App\Http\Resources\Api\Frontend\BasicBlogResource;
use App\Http\Resources\Api\Frontend\BlogResource;
use App\Http\Resources\Api\Frontend\LimitedDealResource;
use App\Models\Blog;
use App\Models\EmailSubscribe;
use App\Models\ProductCategory;
use App\Models\ProductVariant;
use App\Models\SearchQuery;
use App\Models\Store;
use App\Services\Frontend\BannerService;
use App\Traits\PaginatesResponse;
use Illuminate\Support\Facades\Auth;
use Vinkla\Hashids\Facades\Hashids;

class HomePageService
{
  use PaginatesResponse;

  public function __construct(protected BannerService $bannerService) {}
  public function getHomePageData(): array
  {
    // Fetch banners using the BannerService
    $homePageOfferBanners         = $this->bannerService->getBanner('ticker', false, 'custom_order');
    $homePageOfferSpeed           = $this->bannerService->getBanner('ticker_speed', true);
    $homePageFourHovercaedTitle           = $this->bannerService->getBanner('four_hover_card_title', true);
    $homePageBannerSlider         = $this->bannerService->getBanner('hero', false, 'custom_order');
    $homePageBSubscribeBanner     = $this->bannerService->getBanner('subscribe_banner', true, 'custom_order');
    $homePageBlockBanner          = $this->bannerService->getBanner('block_wrap', true, 'custom_order');
    $homePageSalesBanner          = $this->bannerService->getBanner('sale_block_fullwidth', true, 'custom_order');
    $homePageKeepFollowingBanner = $this->bannerService->getBanner('flow_banner', true, 'custom_order');
    $homePageBrandSlider          = $this->bannerService->getBanner('brand_carousel', false, 'custom_order')->take(10);

    // Hover card banners with SKU mapping
    $furnitureCategories = $this->bannerService->getBanner('hover_card', false, 'custom_order')->take(3);
    $furnitureCategorySkus = $furnitureCategories->map(function ($banner) {
      $settings = json_decode($banner->settings, true);
      return $settings['product_sku'] ?? null;
    })->filter()->unique()->values();

    $furnitureCategoryProductVariants = ProductVariant::whereIn('sku', $furnitureCategorySkus)->get()->keyBy('sku');

    $furnitureCategoriesMergedData = $furnitureCategories->map(function ($banner) use ($furnitureCategoryProductVariants) {
      $settings = json_decode($banner->settings, true);
      $sku = $settings['product_sku'] ?? null;
      $variant = $sku ? $furnitureCategoryProductVariants->get($sku) : null;

      return [
        'furnitureCategoryBanner' => $banner,
        'furnitureCategoryProductVariant' => $variant,
      ];
    });

    // Four hover card banners with SKU mapping
    $furnitureContemporaries = $this->bannerService->getBanner('four_hover_cards', false, 'custom_order')->take(7);
    $furnitureContemporariesSkus = $furnitureContemporaries->map(function ($banner) {
      $settings = json_decode($banner->settings, true);
      return $settings['product_sku'] ?? null;
    })->filter()->unique()->values();

    $furnitureContemporaryProductVariants = ProductVariant::whereIn('sku', $furnitureContemporariesSkus)->get()->keyBy('sku');

    $furnitureContemporaryMergedData = $furnitureContemporaries->map(function ($banner) use ($furnitureContemporaryProductVariants) {
      $settings = json_decode($banner->settings, true);
      $sku = $settings['product_sku'] ?? null;
      $variant = $sku ? $furnitureContemporaryProductVariants->get($sku) : null;

      return [
        'furnitureContemporaryBaner' => $banner,
        'furnitureContemporaryProductVariant' => $variant,
      ];
    });


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

    $recentlyViewed = [];

    if (!Auth::check()) {
       $lastLoggedSearchQueries = session('last_logged_search_query', []);

        // Ensure it’s an array (fix for the "count()" error)
        if (!is_array($lastLoggedSearchQueries)) {
            $lastLoggedSearchQueries = [$lastLoggedSearchQueries];
        }

        // Filter out empty values, just in case
        $lastLoggedSearchQueries = array_filter($lastLoggedSearchQueries);

        $recentlyViewed = !empty($lastLoggedSearchQueries)
            ? ProductVariant::whereIn('sku', $lastLoggedSearchQueries)
                ->with([
                    'images' => function ($query) {
                        $query->orderByDesc('is_default');
                    },
                    'product',
                    'category',
                    'inventory',
                    'variantAttributes.attributeValue',
                    'colorOptions.attributeValue',
                ])
                ->take(10)
                ->get()
            : collect();
    } else {
      $searchQueries = SearchQuery::where('user_id', auth()->id())
        ->where('ip_address', request()->ip())
        ->pluck('query');

      $recentlyViewed = ProductVariant::whereIn('sku', $searchQueries)
        ->with([
          'images' => function ($query) {
            $query->orderByDesc('is_default');
          },
          'product',
          'category',
          'inventory',
          'variantAttributes.attributeValue',
          'colorOptions.attributeValue',
        ])
        ->orderByDesc('id')
        ->take(10)
        ->get();
    }

    $stores = Store::where('status', 1)->take(8)->get();

    return [
      'title'                    => 'Home',
      'productCategories'       => $topFourProductCategoriesData,
      'mainSliders'             => $homePageBannerSlider->take(4),
      'homePageOfferBanners'    => $homePageOfferBanners,
      'homePageOfferSpeed'      => $homePageOfferSpeed,
      'homePageFourHovercaedTitle'      => $homePageFourHovercaedTitle,
      'furnitureCategories'     => $furnitureCategoriesMergedData,
      'blockWrap'               => $homePageBlockBanner,
      'brandSliders'            => $homePageBrandSlider,
      'furnitureContemporaries' => $furnitureContemporaryMergedData,
      'furnitureSaleBlock'      => $homePageSalesBanner,
      'keepFlowing'             => $homePageKeepFollowingBanner,
      'subscribe'               => $homePageBSubscribeBanner,
      'recentlyViewedPrtoducts' => $recentlyViewed,
      'stores'                  => $stores
    ];
  }

  public function getBlogs($params, $list = 'all')
  {
    $currentPage = $params['page'] ?? 1;
    $perPage = $params['per_page'] ?? 10;

    $blogs = Blog::with('post')->paginate($perPage, ['*'], 'page', $currentPage);

    $formattedBlogs = $list === 'min'
      ? BasicBlogResource::collection($blogs)
      : BlogResource::collection($blogs);

    return [
      'blogs' => $formattedBlogs,
      'pagination' => $this->formatPagination($blogs, $currentPage, $perPage),
    ];
  }

  public function getLimitedTimeDeals(array $params): array
  {
    $page = (int) ($params['page'] ?? 1);
    $perPage = (int) ($params['per_page'] ?? 10);

    $variants = ProductVariant::where('status', 1)
      ->with('product', 'galleries')
      ->whereHas('product')
      ->get()
      ->filter(function ($variant) {
        $saleInfo = findSalePrice($variant->id);
        return $saleInfo && $saleInfo['promotion_id'] !== null;
      });

    $paginated = $variants->slice(($page - 1) * $perPage, $perPage)->values();

    return [
      'deals' => LimitedDealResource::collection($paginated),
      'pagination' => $this->formatPagination($variants, $page, $perPage),
    ];
  }

  public function subscribeEmailService($request)
  {
    $response = EmailSubscribe::subscribeEmail($request);

    if ($response->getData()->success === true) {
      $email = $request->email;

      $subscriber = EmailSubscribe::where('email', $email)->first();

      $hashedId = Hashids::encode($subscriber->id);

      $confirmationUrl = route('email.confirm', ['hash' => $hashedId]);

      // Prepare email params
      $subject = 'Confirm Your Email Subscription';
      $template = 'emails.frontend.email-subscription';
      $params = [
        'email' => $email,
        'confirmationUrl' => $confirmationUrl, // Pass this to the Blade view
      ];

      // Send email
      app('EmailService')->sendEmail($email, $subject, $template, $params);
    }

    return $response;
  }

  public function confirmEmailService($hash)
  {
    $decoded = Hashids::decode($hash);
    if (count($decoded) === 0) {
      return ['message' => 'Invalid or expired confirmation link.'];
    }

    $id = $decoded[0];
    $subscriber = EmailSubscribe::find($id);

    if (!$subscriber) {
      return ['message' => 'Subscriber not found.'];
    }

    $subscriber->is_subscribe = 1;
    $subscriber->save();

    return ['message' => 'Your email has been successfully confirmed!'];
  }

  public function getAppFooterMenus()
  {
    return [
      'footer-menu' => [
        ['title' => 'Notifications', 'link' => '/notifications', 'method' => 'GET'],
        ['title' => 'How to Return', 'link' => '/help/how-to-return', 'method' => 'GET'],
        ['title' => 'How Do I Redeem My Coupon?', 'link' => '/help/redeem-coupon', 'method' => 'GET'],
        ['title' => 'Promotions', 'link' => '/promotions', 'method' => 'GET'],
        ['title' => 'Return and Refund Policy', 'link' => '/help/return-policy', 'method' => 'GET'],
        ['title' => 'Apply Coupon', 'link' => '/apply-coupon', 'method' => 'GET'],
        ['title' => 'Invite a Friend', 'link' => '/invite', 'method' => 'GET'],
        ['title' => 'Charges and Payments', 'link' => '/help/charges-payments', 'method' => 'GET'],
        ['title' => 'Customer Service', 'link' => '/customer-service', 'method' => 'GET'],
        ['title' => 'Sign Out', 'link' => '/logout', 'method' => 'POST'],
      ]
    ];
  }
}
