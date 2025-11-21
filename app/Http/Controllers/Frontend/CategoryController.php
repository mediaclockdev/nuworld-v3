<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\FilterRequest;
use App\Services\Frontend\BannerService;
use App\Services\Frontend\CategoryService;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService, protected BannerService $bannerService) {}

    public function index()
    {
        return view(
            'frontend.pages.categories.index',
            [
                'title' => 'Categories',

                'productCategories' => $this->categoryService->getCategoriesWithProducts(4, 'latest'),
                'popularCategories' => $this->categoryService->getCategoriesWithProducts(8),
                'categoryBanner' => $this->bannerService->getBanner('category_page_banner', true),
                'categorySaleBlock' => $this->bannerService->getBanner('category_sale_block', true),
                'categoryHotDealsBlock' => $this->bannerService->getBanner('hot_deals_category_banner', false, 'custom_order'),
                'categoryHeadlineBanner' => $this->bannerService->getBanner('category_page_headline_banner', true),
            ]
        );
    }

    // public function bySlug(FilterRequest $request, string $slug)
    // {

    //   $category = $this->categoryService->getCategory($slug);
    //   //pd($category->parent->parent->id);
    //   $productListingBanner = $this->bannerService->getBanner('shop_details_page_banner', false);
    //   pd($productListingBanner);

    //   if (!$category) {
    //     return response()->view('frontend.errors.404', [], 404);
    //   }

    //   $childCategories = $this->categoryService->getChildCategories($category);
    //   if ($childCategories->isNotEmpty()) {
    //     return view('frontend.pages.categories.sub-categories', [
    //       'childCategories' => $childCategories,
    //       'title' => $category->title ?? '',
    //       'categoryHotDealsBlock' => $this->bannerService->getBanner('hot_deals_category_banner', false, 'custom_order'),
    //       'categoryBanner' => $this->bannerService->getBanner('category_page_banner', true),
    //       'categoryHeadlineBanner' => $this->bannerService->getBanner('category_page_headline_banner', true),
    //     ]);
    //   }

    //   $products = $this->categoryService->getDisplayProducts($category);

    //   if ($products->isEmpty()) {
    //     return response()->view('frontend.errors.404', [], 404);
    //   }
    //   return view('frontend.pages.categories.show', array_merge(
    //     $this->categoryService->prepareViewData($category, $products, $request, $slug),
    //     // ['productListingBanner' => $this->bannerService->getBanner('shop_page_banner', true)]
    //     ['productListingBanner' => $this->bannerService->getBanner('shop_details_page_banner', false)]
    //   ));
    // }

    public function bySlug(FilterRequest $request, string $slug)
    {
        $category = $this->categoryService->getCategory($slug);

        if (! $category) {
            return response()->view('frontend.errors.404', [], 404);
        }

        // Determine which category ID to use for matching banners
        $targetCategoryId = $category->parent->parent->id ?? 0;

        // Fetch all banners for 'shop_details_page_banner'
        $banners = $this->bannerService->getBanner('shop_details_page_banner', false);

        // Find banner that matches the target category ID in "single_option"
        $productListingBanner = collect($banners)->first(function ($banner) use ($targetCategoryId) {
            $settings = json_decode($banner['settings'], true);

            return isset($settings['single_option']) && $settings['single_option'] == $targetCategoryId;
        });

        // Optional fallback: use the first available banner if no match found
        // if (! $productListingBanner) {
        //     $productListingBanner = collect($banners)->first();
        // }

        $childCategories = $this->categoryService->getChildCategories($category);
        if ($childCategories->isNotEmpty()) {
            return view('frontend.pages.categories.sub-categories', [
                'childCategories' => $childCategories,
                'title' => $category->title ?? '',
                // 'slug' => $category->slug ?? '',
                'categoryHotDealsBlock' => $this->bannerService->getBanner('hot_deals_category_banner', false, 'custom_order'),
                'categoryBanner' => $this->bannerService->getBanner('category_page_banner', true),
                'categoryHeadlineBanner' => $this->bannerService->getBanner('category_page_headline_banner', true),
            ]);
        }

        $products = $this->categoryService->getDisplayProducts($category);

        if ($products->isEmpty()) {
            return response()->view('frontend.errors.404', [], 404);
        }

        return view('frontend.pages.categories.show', array_merge(
            $this->categoryService->prepareViewData($category, $products, $request, $slug),
            ['productListingBanner' => $productListingBanner,
            'category_slug' => $category->slug ?? '',
            ]
        ));
    }
}
