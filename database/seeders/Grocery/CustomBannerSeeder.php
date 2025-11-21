<?php

namespace Database\Seeders\Grocery;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{Storage, File};
use Illuminate\Support\Facades\DB;

class CustomBannerSeeder extends Seeder
{
  public function run(): void
  {
    $this->seedBanners();
    $this->uploadBannerImages();
  }
  private function seedBanners(): void
  {
    $skus = DB::table('product_variants')->inRandomOrder()->limit(15)->pluck('sku')->toArray();
    $url = env('APP_URL');
    $now = now()->toDateTimeString();
    $banners = array_merge(
      $this->getTickerBanners($url, $skus),
      $this->getHeroBanners($url, $skus),
      $this->getHoverCardBanners($skus),
      $this->getBlockWrapBanner($url),
      $this->getBrandCarouselBanners(),
      $this->getAdditionalBanners($url, $skus)
    );
    foreach ($banners as $banner) {
      DB::table('custom_banners')->insert([
        'id' => $banner['id'],
        'title' => $banner['title'],
        'position' => $banner['position'],
        'settings' => json_encode($banner['settings']),
        'custom_order' => $banner['custom_order'],
        'status' => 1,
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => $banner['deleted_at'] ?? null,
      ]);
    }
  }
  private function getTickerBanners(string $url, array $skus): array
  {
    return [
      ['id' => 1, 'title' => 'Fresh Fruits & Veggies starting at just $199', 'position' => 'ticker', 'settings' => ['speed' => '30000', 'hyper_link' => "$url/product/{$skus[0]}"], 'custom_order' => 6],
      ['id' => 2, 'title' => 'Buy 1 Get 1 Free: Organic Snacks – Limited Time!', 'position' => 'ticker', 'settings' => ['speed' => '30000', 'hyper_link' => "$url/product/{$skus[1]}"], 'custom_order' => 5],
      ['id' => 3, 'title' => 'Free Delivery on grocery orders over $300', 'position' => 'ticker', 'settings' => ['speed' => '30000', 'hyper_link' => "$url/product/{$skus[2]}"], 'custom_order' => 2, 'deleted_at' => '2025-05-30 01:18:09'],
      ['id' => 4, 'title' => 'Weekly Deals: Dairy Essentials starting at $399', 'position' => 'ticker', 'settings' => ['speed' => '5000', 'hyper_link' => "$url/product/{$skus[3]}"], 'custom_order' => 4],
      ['id' => 5, 'title' => 'Free Delivery on pantry staples over $200', 'position' => 'ticker', 'settings' => ['speed' => '5000', 'hyper_link' => "$url/product/{$skus[4]}"], 'custom_order' => 3],
      ['id' => 6, 'title' => 'Exclusive: Free Delivery on Fresh Produce Orders', 'position' => 'ticker', 'settings' => ['speed' => '5000', 'hyper_link' => "$url/product/{$skus[5]}"], 'custom_order' => 2],
      ['id' => 7, 'title' => 'Free Shipping on orders over $300', 'position' => 'ticker', 'settings' => ['speed' => '30000', 'hyper_link' => "$url/product/{$skus[6]}"], 'custom_order' => 1],
    ];
  }
  private function getHeroBanners(string $url, array $skus): array
  {
    return [
      ['id' => 8, 'title' => 'Hero Slider 1', 'position' => 'hero', 'settings' => ['image' => 'g-homeslider1.webp', 'content' => '<p>Freshness Delivered<br>To Your Kitchen Every Day</p>', 'alt_text' => 'Fresh groceries hero banner', 'hyper_link' => "$url/product/{$skus[7]}", 'default_image_size' => null], 'custom_order' => 1],
      ['id' => 9, 'title' => 'Hero Slider 2', 'position' => 'hero', 'settings' => ['image' => 'g-homeslider2.webp', 'content' => '<p>Limited-Time Offer:<br>Save up to 40% on Pantry Essentials</p>', 'alt_text' => 'Discounted pantry items', 'hyper_link' => "$url/product/{$skus[8]}", 'default_image_size' => null], 'custom_order' => 5],
      ['id' => 10, 'title' => 'Hero Slider 3', 'position' => 'hero', 'settings' => ['image' => 'g-homeslider3.webp', 'content' => '<p>Up to 30% Off:<br>Fruits, Veggies & More!</p>', 'alt_text' => 'Weekly grocery savings', 'hyper_link' => "$url/product/{$skus[9]}", 'default_image_size' => null], 'custom_order' => 27],
    ];
  }
  private function getHoverCardBanners(array $skus): array
  {
    return [
      ['id' => 11, 'title' => 'Left Hover Card', 'position' => 'hover_card', 'settings' => ['image' => 'g-category_threeblocks_th1.webp', 'content' => '<h4 class="font16 fw-normal text-center mb-0">New Arrival</h4><h3 class="font35 fw-normal text-center">Fresh Vegetables</h3>', 'alt_text' => 'ssdas', 'btn_text' => 'View', 'btn_color' => '#42388F', 'hyper_link' => null, 'product_sku' => $skus[10], 'default_image_size' => null], 'custom_order' => 6],
      ['id' => 12, 'title' => 'Hover card Middle', 'position' => 'hover_card', 'settings' => ['image' => 'g-category_threeblocks_th2.webp', 'content' => '<div class="top flow-rootx2 c--blackc"><h4 class="font14 fw-normal text-center mb-0">New Arrival</h4><h3 class="font30 fw-normal text-center">Organic Fruits</h3></div><div class="pricebox font20 text-center"><span>$</span>750.00</div>', 'alt_text' => 'alt', 'btn_text' => 'View', 'btn_color' => '#000000', 'hyper_link' => null, 'product_sku' => $skus[11], 'default_image_size' => null], 'custom_order' => 8],
      ['id' => 13, 'title' => 'Hover Right', 'position' => 'hover_card', 'settings' => ['image' => 'g-category_threeblocks_th3.webp', 'content' => '<h4 class="font16 fw-normal text-center mb-0">New Arrival</h4><h3 class="font35 fw-normal text-center">Daily Essentials</h3>', 'alt_text' => 'alt', 'btn_text' => 'View', 'btn_color' => '#000000', 'hyper_link' => null, 'product_sku' => $skus[12], 'default_image_size' => null], 'custom_order' => 9],
    ];
  }
  private function getBlockWrapBanner(string $url): array
  {
    return [
      ['id' => 14, 'title' => 'Block Wrap', 'position' => 'block_wrap', 'settings' => ['image' => 'g-sleek-furniture.webp', 'content' => '<p>Delight in every craving with fresh, frozen, dine-in, delivery, and take-away all in one place</p>', 'alt_text' => 'alt', 'btn_text' => 'View All Collections', 'btn_color' => '#000000', 'hyper_link' => "$url/categories", 'default_image_size' => null], 'custom_order' => 10],
    ];
  }
  private function getBrandCarouselBanners(): array
  {
    $logos = ['g-brandlogo1.webp', 'g-brandlogo2.webp', 'g-brandlogo3.webp', 'g-brandlogo4.webp', 'g-brandlogo5.webp', 'g-brandlogo6.webp'];
    $banners = [];
    for ($i = 0; $i < 10; $i++) {
      $banners[] = [
        'id' => 15 + $i,
        'title' => "Brand Carousel " . ($i + 1),
        'position' => 'brand_carousel',
        'settings' => ['image' => $logos[$i], 'alt_text' => $i < 2 ? 'alt' : null, 'default_image_size' => null],
        'custom_order' => 11 + $i + ($i > 6 ? 10 : 0),
      ];
    }
    return $banners;
  }
  private function getAdditionalBanners(string $url, array $skus): array
  {
    return [
      ['id' => 25, 'title' => 'Furniture contemporary 1', 'position' => 'four_hover_cards', 'settings' => ['image' => 'g-contemporary1.webp', 'content' => '<p>Fresh Picks for Every Pantry</p>', 'alt_text' => 'alt text', 'btn_text' => 'View', 'btn_color' => '#000000', 'hyper_link' => null, 'default_image_size' => null], 'custom_order' => 30],
      ['id' => 26, 'title' => 'Furniture contemporary 2', 'position' => 'four_hover_cards', 'settings' => ['image' => 'g-contemporary2.webp', 'content' => '<p>Organic Goodness Delivered</p>', 'alt_text' => 'alt text', 'btn_text' => 'View', 'btn_color' => '#000000', 'hyper_link' => null, 'default_image_size' => null], 'custom_order' => 31],
      ['id' => 27, 'title' => 'Furniture contemporary 3', 'position' => 'four_hover_cards', 'settings' => ['image' => 'g-contemporary4.webp', 'content' => '<p>Everyday Essentials, Always Fresh</p>', 'alt_text' => 'alt text', 'btn_text' => null, 'btn_color' => '#000000', 'hyper_link' => null, 'default_image_size' => null], 'custom_order' => 32],
      ['id' => 28, 'title' => 'Furniture contemporary 4', 'position' => 'four_hover_cards', 'settings' => ['image' => 'g-contemporary5.webp', 'content' => '<p>Deals on Daily Grocery Staples</p>', 'alt_text' => 'alt text', 'btn_text' => 'View', 'btn_color' => '#000000', 'hyper_link' => null, 'default_image_size' => null], 'custom_order' => 33],
      ['id' => 29, 'title' => 'Grocery Made Easy with Functional Convenience', 'position' => 'sale_block_fullwidth', 'settings' => ['image' => 'g-sales_block_img.webp', 'content' => '<div class="blk"><h2 class="fw-normal c--whitec font80 m-0">SALE</h2><h4 class="fw-normal c--whitec font25">Up to 50% Off</h4></div>', 'alt_text' => null, 'btn_text' => 'View Collections', 'btn_color' => '#000000', 'hyper_link' => "$url/categories", 'default_image_size' => null], 'custom_order' => 34],
      ['id' => 30, 'title' => 'Farm - Fresh Every Day', 'position' => 'flow_banner', 'settings' => ['image' => 'g-furniture__keepfrowing.webp', 'content' => '<p><span>Fresh</span> <span>Grocery</span> <span>Deals</span></p>', 'alt_text' => 'alt text', 'default_image_size' => null], 'custom_order' => 35],
      ['id' => 31, 'title' => 'Subscribe', 'position' => 'subscribe_banner', 'settings' => ['content' => '<p>Harvested vegetables bursting with flavor and nutrition, delivered daily. Enjoy farm-fresh goodness in every meal with our special deal.</p>'], 'custom_order' => 36],
      ['id' => 32, 'title' => 'Shop Page', 'position' => 'shop_page_banner', 'settings' => ['image' => 'g-product-hero.webp', 'default_image_size' => null, 'alt_text' => null], 'custom_order' => 6],
      ['id' => 33, 'title' => 'Crafted with innovation', 'position' => 'category_page_banner', 'settings' => ['image' => 'g-category_hero.webp', 'default_image_size' => null, 'alt_text' => null, 'content' => '<p class="act font25" data-parallax-strength-vertical="-2.5" data-parallax-height="-2.5"><span data-parallax-target="">Our groceries are fresh, easy to order,</span><br><span data-parallax-target="">and convenient for your daily needs.</span></p>'], 'custom_order' => 6],
      ['id' => 34, 'title' => 'Hotdeal 1', 'position' => 'hot_deals_category_banner', 'settings' => ['image' => 'g-hot_deals1.webp', 'default_image_size' => null, 'alt_text' => '#', 'hyper_link' => 'https://www.google.com'], 'custom_order' => 6],
      ['id' => 35, 'title' => 'hotdeal 2', 'position' => 'hot_deals_category_banner', 'settings' => ['image' => 'g-hot_deals2.webp', 'default_image_size' => null, 'alt_text' => null, 'hyper_link' => null], 'custom_order' => 6],
      ['id' => 36, 'title' => 'Hotdeal 3', 'position' => 'hot_deals_category_banner', 'settings' => ['image' => 'g-hot_deals3.webp', 'default_image_size' => null, 'alt_text' => null, 'hyper_link' => 'https://www.google.com'], 'custom_order' => 6],
      ['id' => 37, 'title' => 'Hotdeal 4', 'position' => 'hot_deals_category_banner', 'settings' => ['image' => 'g-hot_deals4.webp', 'default_image_size' => null, 'alt_text' => null, 'hyper_link' => null], 'custom_order' => 6],
      ['id' => 38, 'title' => 'Grocery Made Easy with Functional Convenience', 'position' => 'category_sale_block', 'settings' => ['image' => 'g-sales_block_img2.webp', 'default_image_size' => null, 'alt_text' => null, 'content' => '<div class="blk"><h2 class="fw-normal c--whitec font80 m-0">SALE</h2><h4 class="fw-normal c--whitec font25">Up to 50% Off</h4></div>', 'btn_text' => 'View Collections', 'btn_color' => '#000000', 'hyper_link' => "$url/categories"], 'custom_order' => 6],
      ['id' => 39, 'title' => 'Grocery Delights', 'position' => 'login_page_banner', 'settings' => ['image' => 'g-signup_popup_thumb.webp', 'default_image_size' => null, 'alt_text' => null, 'content' => '<p>Discover 30k+ varities</p>'], 'custom_order' => 6],
      ['id' => 40, 'title' => 'Experience the best of grocery shopping', 'position' => 'category_page_headline_banner', 'settings' => [], 'custom_order' => 6],
      ['id' => 41, 'title' => '', 'position' => 'app_splash_logo', 'settings' => ['image' => 'g-app_splash_logo.webp', 'alt_text' => 'alt', 'bg_color' => '#2FABCA', 'default_image_size' => null], 'custom_order' => 5],
      ['id' => 42, 'title' => 'App Journey Begins', 'position' => 'app_journey_screen', 'settings' => ['image' => 'g-app_journey_screen.webp', 'alt_text' => 'alt', 'btn_text' => 'Explore Now', 'btn_color' => '#000000', 'show_button' => true, 'skip_btn_text' => 'Skip', 'skip_btn_color' => '#000000', 'show_skip_button' => true, 'default_image_size' => null], 'custom_order' => 5],
      ['id' => 43, 'title' => '', 'position' => 'app_category_page_checkout_collections', 'settings' => ['image' => 'g-app_category_page_checkout_collections1.webp', 'alt_text' => 'alt text', 'hyper_link' => 'https://www.yahoo.com/', 'banner_type' => null, 'default_image_size' => null], 'custom_order' => 5],
      ['id' => 44, 'title' => '', 'position' => 'app_category_page_checkout_collections', 'settings' => ['image' => 'g-app_category_page_checkout_collections2.webp', 'alt_text' => 'alt text', 'hyper_link' => 'https://www.yahoo.com/', 'banner_type' => null, 'default_image_size' => null], 'custom_order' => 5],
      ['id' => 45, 'title' => '', 'position' => 'app_category_page_checkout_collections', 'settings' => ['image' => 'g-app_category_page_checkout_collections3.webp', 'alt_text' => 'alt text', 'hyper_link' => 'https://www.yahoo.com/', 'banner_type' => null, 'default_image_size' => null], 'custom_order' => 5],
      ['id' => 46, 'title' => '', 'position' => 'four_hover_card_title', 'settings' => ['title' => 'Fresh Grocery'], 'custom_order' => 1],

      ['id' => 47, 'title' => '', 'position' => 'app_home_landing_inner_banner', 'settings' => ['image' => 'g-home_sub_banner_1.webp', 'alt_text' => 'alt text', 'hyper_link' => 'https://www.yahoo.com/', 'banner_type' => null, 'default_image_size' => null], 'custom_order' => 5],
      ['id' => 48, 'title' => '', 'position' => 'app_home_landing_inner_banner', 'settings' => ['image' => 'g-home_sub_banner_2.webp', 'alt_text' => 'alt text', 'hyper_link' => 'https://www.google.com/', 'banner_type' => null, 'default_image_size' => null], 'custom_order' => 1],
      ['id' => 49, 'title' => '', 'position' => 'app_home_landing_inner_banner', 'settings' => ['image' => 'g-home_sub_banner_3.webp', 'alt_text' => 'alt text', 'hyper_link' => 'https://www.yahoo.com/', 'banner_type' => null, 'default_image_size' => null], 'custom_order' => 2],
      ['id' => 50, 'title' => '', 'position' => 'home_page_top_category_banner', 'settings' => ['options' => [3, 6, 9, 20], 'banner_type' => null], 'custom_order' => 6, 'created_at' => null, 'updated_at' => null, 'deleted_at' => null],


    ];
  }
  private function uploadBannerImages(): void
  {
    $relativePath = 'uploads/banners';
    $disk = Storage::disk('public');
    $destinationPath = storage_path("app/public/{$relativePath}");
    $sourcePath = public_path('SeederImages/Grocery/banners');
    if (File::exists($destinationPath)) {
      File::deleteDirectory($destinationPath);
    }
    $disk->makeDirectory($relativePath);
    if (!File::exists($sourcePath)) {
      throw new \Exception("Source path does not exist: {$sourcePath}");
    }
    foreach (File::files($sourcePath) as $file) {
      File::copy($file->getPathname(), "{$destinationPath}/{$file->getFilename()}");
    }
  }
}
