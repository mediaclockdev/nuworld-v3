<?php

namespace Database\Seeders\Sunglasses;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{Storage, File};
use Illuminate\Support\Facades\DB;

class CustomBannerSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
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
      $this->getAdditionalBanners($url)
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
        'created_by' => $banner['created_by'] ?? null,
        'updated_by' => $banner['updated_by'] ?? null,
      ]);
    }
  }

  private function getTickerBanners(string $url, array $skus): array
  {
    return [
      ['id' => 1, 'title' => 'Sunglasses Starting at $49', 'position' => 'ticker', 'settings' => ['speed' => '30000', 'hyper_link' => "$url/product/{$skus[0]}"], 'custom_order' => 6],
      ['id' => 2, 'title' => 'Limited Time Offer: Free Case with Every Sunglass Purchase', 'position' => 'ticker', 'settings' => ['speed' => '30000', 'hyper_link' => "$url/product/{$skus[1]}"], 'custom_order' => 5],
      ['id' => 3, 'title' => 'Free Shipping on Orders Over $100', 'position' => 'ticker', 'settings' => ['speed' => '30000', 'hyper_link' => "$url/product/{$skus[2]}"], 'custom_order' => 2, 'deleted_at' => '2025-05-30 01:18:09'],
      ['id' => 4, 'title' => 'Premium Polarized Sunglasses from $99', 'position' => 'ticker', 'settings' => ['speed' => '5000', 'hyper_link' => "$url/product/{$skus[3]}"], 'custom_order' => 4],
      ['id' => 5, 'title' => 'Free Shipping on All Designer Shades', 'position' => 'ticker', 'settings' => ['speed' => '5000', 'hyper_link' => "$url/product/{$skus[4]}"], 'custom_order' => 3],
      ['id' => 6, 'title' => 'Buy 1 Get 1 50% Off – Limited Time Sunglass Sale', 'position' => 'ticker', 'settings' => ['speed' => '5000', 'hyper_link' => "$url/product/{$skus[5]}"], 'custom_order' => 2],
      ['id' => 7, 'title' => 'Stylish Summer Sunglasses Under $75', 'position' => 'ticker', 'settings' => ['speed' => '30000', 'hyper_link' => "$url/product/{$skus[6]}"], 'custom_order' => 1],
    ];
  }

  private function getHeroBanners(string $url, array $skus): array
  {
    return [
      ['id' => 8, 'title' => 'Hero Slider 1', 'position' => 'hero', 'settings' => ['image' => 's-homeslider1.webp', 'content' => '<p>Find Your Perfect Pair<br>Stylish Shades for Every Occasion</p>', 'alt_text' => 'ssdas', 'hyper_link' => "$url/product/{$skus[7]}", 'default_image_size' => null], 'custom_order' => 1],
      ['id' => 9, 'title' => 'Hero Slider 2', 'position' => 'hero', 'settings' => ['image' => 's-homeslider2.webp', 'content' => '<p>Limited-Time Offer:<br>Up to 40% Off on Sunglasses</p>', 'alt_text' => 'alt text', 'hyper_link' => "$url/product/{$skus[8]}", 'default_image_size' => null], 'custom_order' => 5],
      ['id' => 10, 'title' => 'Hero Slider 3', 'position' => 'hero', 'settings' => ['image' => 's-homeslider3.webp', 'content' => '<p>Exclusive Deal:<br>30% Off Polarized Sunglasses – Today Only!</p>', 'alt_text' => null, 'hyper_link' => "$url/product/{$skus[9]}", 'default_image_size' => null], 'custom_order' => 27],
    ];
  }

  private function getHoverCardBanners(array $skus): array
  {
    return [
      ['id' => 11, 'title' => 'Left Hover Card', 'position' => 'hover_card', 'settings' => ['image' => 's-category_threeblocks_th1.webp', 'content' => '<h4 class="font16 fw-normal text-center mb-0">New Arrival</h4><h3 class="font35 fw-normal text-center">Sunglasses</h3>', 'alt_text' => 'ssdas', 'btn_text' => 'View', 'btn_color' => '#42388f', 'hyper_link' => null, 'product_sku' => $skus[10], 'default_image_size' => null], 'custom_order' => 6],
      ['id' => 12, 'title' => 'Middle Hover Card', 'position' => 'hover_card', 'settings' => ['image' => 's-category_threeblocks_th2.webp', 'content' => '<div class="top flow-rootx2 c--blackc"><h4 class="font14 fw-normal text-center mb-0">New Arrival</h4><h3 class="font30 fw-normal text-center">Sunglasses</h3></div><div class="pricebox font20 text-center"><span>$</span>129.00</div>', 'alt_text' => 'alt', 'btn_text' => 'View', 'btn_color' => '#000000', 'hyper_link' => null, 'product_sku' => $skus[11], 'default_image_size' => null], 'custom_order' => 8],
      ['id' => 13, 'title' => 'Right Hover Card', 'position' => 'hover_card', 'settings' => ['image' => 's-category_threeblocks_th3.webp', 'content' => '<h4 class="font16 fw-normal text-center mb-0">New Arrival</h4><h3 class="font35 fw-normal text-center">Sunglasses</h3>', 'alt_text' => 'alt', 'btn_text' => 'View', 'btn_color' => '#000000', 'hyper_link' => null, 'product_sku' => $skus[12], 'default_image_size' => null], 'custom_order' => 9],
    ];
  }


  private function getBlockWrapBanner(string $url): array
  {
    return [
      ['id' => 14, 'title' => 'Block Wrap', 'position' => 'block_wrap', 'settings' => ['image' => 's-sleek-furniture.webp', 'content' => '<p>Discover our curated collection of sleek, statement-making sunglasses — where fashion meets function in every frame.</p>', 'alt_text' => 'alt', 'btn_text' => 'View All Collections', 'btn_color' => '#000000', 'hyper_link' => "$url/categories", 'default_image_size' => null], 'custom_order' => 10],
    ];
  }

  private function getBrandCarouselBanners(): array
  {
    $logos = ['s-brandlogo1.webp', 's-brandlogo2.webp', 's-brandlogo3.webp', 's-brandlogo4.webp', 's-brandlogo5.webp', 's-brandlogo6.webp', 's-brandlogo7.webp', 's-brandlogo3.webp', 's-brandlogo1.webp', 's-brandlogo2.webp'];
    $banners = [];
    for ($i = 0; $i < 10; $i++) {
      $banners[] = [
        'id' => 15 + $i,
        'title' => "Brand Carousel " . ($i + 1),
        'position' => 'brand_carousel',
        'settings' => ['image' => $logos[$i], 'alt_text' => $i < 2 ? 'alt' : ($i == 2 ? 'alt text' : null), 'default_image_size' => null],
        'custom_order' => 11 + $i + ($i > 6 ? 10 : 0),
      ];
    }
    return $banners;
  }

  private function getAdditionalBanners(string $url): array
  {
    return [

      ['id' => 25, 'title' => 'Sunglass Contemporary 1', 'position' => 'four_hover_cards', 'settings' => ['image' => 's-contemporary1.webp', 'content' => '<p>Bold Looks. Clear Vision.</p>', 'alt_text' => 'alt text', 'btn_text' => 'View', 'btn_color' => '#000000', 'hyper_link' => null, 'default_image_size' => null], 'custom_order' => 30],
      ['id' => 26, 'title' => 'Sunglass Contemporary 2', 'position' => 'four_hover_cards', 'settings' => ['image' => 's-contemporary2.webp', 'content' => '<p>Elevate Your Everyday Style</p>', 'alt_text' => 'alt text', 'btn_text' => 'View', 'btn_color' => '#000000', 'hyper_link' => null, 'default_image_size' => null], 'custom_order' => 31],
      ['id' => 27, 'title' => 'Sunglass Contemporary 3', 'position' => 'four_hover_cards', 'settings' => ['image' => 's-contemporary4.webp', 'content' => '<p>Sun-Ready & Fashion-Forward</p>', 'alt_text' => 'alt text', 'btn_text' => null, 'btn_color' => '#000000', 'hyper_link' => null, 'default_image_size' => null], 'custom_order' => 32],
      ['id' => 28, 'title' => 'Sunglass Contemporary 4', 'position' => 'four_hover_cards', 'settings' => ['image' => 's-contemporary5.webp', 'content' => '<p>Frames That Define You</p>', 'alt_text' => 'alt text', 'btn_text' => 'View', 'btn_color' => '#000000', 'hyper_link' => null, 'default_image_size' => null], 'custom_order' => 33],


      ['id' => 29, 'title' => 'Style in Every Shade – Sunglass Sale', 'position' => 'sale_block_fullwidth', 'settings' => ['image' => 's-sales_block_img.webp', 'content' => '<div class="blk"><h2 class="fw-normal c--whitec font80 m-0">SALE</h2><h4 class="fw-normal c--whitec font25">Up to 50% Off Sunglasses</h4></div>', 'alt_text' => null, 'btn_text' => 'View Collections', 'btn_color' => '#000000', 'hyper_link' => "$url/categories", 'default_image_size' => null], 'custom_order' => 34],
      ['id' => 30, 'title' => 'Keep It Shaded', 'position' => 'flow_banner', 'settings' => ['image' => 's-furniture_keepflowing.webp', 'content' => '<p><span>Keep</span> <span>It</span> <span>Shaded</span></p>', 'alt_text' => 'alt text', 'default_image_size' => null], 'custom_order' => 35],
      ['id' => 31, 'title' => 'Subscribe', 'position' => 'subscribe_banner', 'settings' => ['content' => '<p>Subscribe now and save up to 15% on selected orders! Discover sunglasses that match your vibe and vision.</p>'], 'custom_order' => 36],
      ['id' => 32, 'title' => 'Shop Page', 'position' => 'shop_page_banner', 'settings' => ['image' => 's-product-hero.webp', 'default_image_size' => null, 'alt_text' => null], 'custom_order' => 6, 'created_by' => 6, 'updated_by' => 6],

      ['id' => 33, 'title' => 'Timeless Designs', 'position' => 'category_page_banner', 'settings' => ['image' => 's-category_hero.webp', 'default_image_size' => null, 'alt_text' => null, 'content' => '<p class="act font25" data-parallax-strength-vertical="-2.5" data-parallax-height="-2.5"><span data-parallax-target="">Our frames are durable, simple to wear,</span><br><span data-parallax-target="">and adaptable to your evolving style.</span></p>'], 'custom_order' => 6, 'created_by' => 6, 'updated_by' => 6],
      ['id' => 34, 'title' => 'Hot Deal 1', 'position' => 'hot_deals_category_banner', 'settings' => ['image' => 's-hot_deals1.webp', 'default_image_size' => null, 'alt_text' => '#', 'hyper_link' => 'https://www.google.com'], 'custom_order' => 6, 'created_by' => 6, 'updated_by' => 6],
      ['id' => 35, 'title' => 'Hot Deal 2', 'position' => 'hot_deals_category_banner', 'settings' => ['image' => 's-hot_deals2.webp', 'default_image_size' => null, 'alt_text' => null, 'hyper_link' => 'https://www.google.com'], 'custom_order' => 6, 'created_by' => 6, 'updated_by' => 6],
      ['id' => 36, 'title' => 'Hot Deal 3', 'position' => 'hot_deals_category_banner', 'settings' => ['image' => 's-hot_deals3.webp', 'default_image_size' => null, 'alt_text' => null, 'hyper_link' => 'https://www.google.com'], 'custom_order' => 6, 'created_by' => 6, 'updated_by' => 6],
      ['id' => 37, 'title' => 'Hot Deal 4', 'position' => 'hot_deals_category_banner', 'settings' => ['image' => 's-hot_deals4.webp', 'default_image_size' => null, 'alt_text' => null, 'hyper_link' => 'https://www.google.com'], 'custom_order' => 6, 'created_by' => 6, 'updated_by' => 6],
      ['id' => 38, 'title' => 'Transform Your Style with Unique Sunglasses', 'position' => 'category_sale_block', 'settings' => ['image' => 's-sales_block_img2.webp', 'default_image_size' => null, 'alt_text' => null, 'content' => '<div class="blk"><h2 class="fw-normal c--whitec font80 m-0">SALE</h2><h4 class="fw-normal c--whitec font25">Up to 50% Off</h4></div>', 'btn_text' => 'View Collections', 'btn_color' => '#000000', 'hyper_link' => "$url/categories"], 'custom_order' => 6, 'created_by' => 6, 'updated_by' => 6],
      ['id' => 39, 'title' => 'Luxury', 'position' => 'login_page_banner', 'settings' => ['image' => 's-signup_popup_thumb.webp', 'default_image_size' => null, 'alt_text' => null, 'content' => '<p>Discover 30k+ varieties</p>'], 'custom_order' => 6, 'created_by' => 6, 'updated_by' => 6],
      ['id' => 40, 'title' => 'Experience the best of sunglass shopping', 'position' => 'category_page_headline_banner', 'settings' => [], 'custom_order' => 6, 'created_by' => 6, 'updated_by' => 6],

      ['id' => 41, 'title' => '', 'position' => 'app_splash_logo', 'settings' => ['image' => 's-app_splash_logo.webp', 'alt_text' => 'alt', 'bg_color' => '#2FABCA', 'default_image_size' => null], 'custom_order' => 5],
      ['id' => 42, 'title' => 'App Journey Begins', 'position' => 'app_journey_screen', 'settings' => ['image' => 's-app_journey_screen.webp', 'alt_text' => 'alt', 'btn_text' => 'Explore Now', 'btn_color' => '#000000', 'show_button' => true, 'skip_btn_text' => 'Skip', 'skip_btn_color' => '#000000', 'show_skip_button' => true, 'default_image_size' => null], 'custom_order' => 5],
      ['id' => 43, 'title' => '', 'position' => 'app_category_page_checkout_collections', 'settings' => ['image' => 's-app_category_page_checkout_collections1.webp', 'alt_text' => 'alt text', 'hyper_link' => 'https://www.yahoo.com/', 'banner_type' => null, 'default_image_size' => null], 'custom_order' => 5],
      ['id' => 44, 'title' => '', 'position' => 'app_category_page_checkout_collections', 'settings' => ['image' => 's-app_category_page_checkout_collections2.webp', 'alt_text' => 'alt text', 'hyper_link' => 'https://www.yahoo.com/', 'banner_type' => null, 'default_image_size' => null], 'custom_order' => 5],
      ['id' => 45, 'title' => '', 'position' => 'app_category_page_checkout_collections', 'settings' => ['image' => 's-app_category_page_checkout_collections3.webp', 'alt_text' => 'alt text', 'hyper_link' => 'https://www.yahoo.com/', 'banner_type' => null, 'default_image_size' => null], 'custom_order' => 5],
      ['id' => 46, 'title' => '', 'position' => 'four_hover_card_title', 'settings' => ['title' => 'Your Everyday Style'], 'custom_order' => 1],

      ['id' => 47, 'title' => '', 'position' => 'app_home_landing_inner_banner', 'settings' => ['image' => 's-home_sub_banner_1.webp', 'alt_text' => 'alt text', 'hyper_link' => 'https://www.yahoo.com/', 'banner_type' => null, 'default_image_size' => null], 'custom_order' => 5],
      ['id' => 48, 'title' => '', 'position' => 'app_home_landing_inner_banner', 'settings' => ['image' => 's-home_sub_banner_2.webp', 'alt_text' => 'alt text', 'hyper_link' => 'https://www.google.com/', 'banner_type' => null, 'default_image_size' => null], 'custom_order' => 1],
      ['id' => 49, 'title' => '', 'position' => 'app_home_landing_inner_banner', 'settings' => ['image' => 's-home_sub_banner_3.webp', 'alt_text' => 'alt text', 'hyper_link' => 'https://www.yahoo.com/', 'banner_type' => null, 'default_image_size' => null], 'custom_order' => 2],
    ];
  }

  private function uploadBannerImages()
  {
    $relativePath    = 'uploads/banners';
    $disk            = Storage::disk('public');
    $destinationPath = storage_path("app/public/{$relativePath}");
    $sourcePath      = public_path('SeederImages/Sunglasses/banners');

    if (File::exists($destinationPath)) {
      File::deleteDirectory($destinationPath);
    }
    $disk->makeDirectory($relativePath);
    // Copy files from source to destination
    if (File::exists($sourcePath)) {
      foreach (File::files($sourcePath) as $file) {
        File::copy($file->getPathname(), "{$destinationPath}/{$file->getFilename()}");
      }
    } else {
      throw new \Exception("Source path does not exist: {$sourcePath}");
    }
  }
}
