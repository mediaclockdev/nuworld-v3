<?php

namespace Database\Seeders\Fashion;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{Storage, File, DB, Hash};

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

  /**
   * Ensure admins that your seeder references exist, create minimal admin rows if they don't.
   * Returns a mapping of original referenced ids => actual admin ids in DB.
   */
  private function ensureRequiredAdmins(array $requiredIds): array
  {
    $map = [];

    foreach ($requiredIds as $origId) {
      // If admin with that id exists, keep it
      $exists = DB::table('admins')->where('id', $origId)->exists();
      if ($exists) {
        $map[$origId] = $origId;
        continue;
      }

      // Try to insert a minimal admin row with that id.
      // Choose a unique email to avoid collisions.
      $email = "seed-admin-{$origId}@" . env('APP_DOMAIN', 'example.com');
      $now = now();

      // If email already exists for a different id, insert without specifying id and capture generated id.
      $emailExists = DB::table('admins')->where('email', $email)->exists();
      if ($emailExists) {
        $newId = DB::table('admins')->where('email', $email)->value('id');
        $map[$origId] = $newId;
        continue;
      }

      try {
        // Attempt insert with explicit id (works if no PK collision)
        DB::table('admins')->insert([
          'id' => $origId,
          'name' => "Seed Admin {$origId}",
          'email' => $email,
          // password hash for 'secret123' (change if you prefer)
          'password' => Hash::make('secret123'),
          'created_at' => $now,
          'updated_at' => $now,
        ]);
        $map[$origId] = $origId;
      } catch (\Exception $e) {
        // If explicit id insert fails (e.g. auto-increment constraint), insert without id and use generated id
        $newId = DB::table('admins')->insertGetId([
          'name' => "Seed Admin {$origId}",
          'email' => $email,
          'password' => Hash::make('secret123'),
          'created_at' => $now,
          'updated_at' => $now,
        ]);
        $map[$origId] = $newId;
      }
    }

    return $map;
  }

  private function seedBanners(): void
  {
    // Fetch a small pool of product variant SKUs
    $skus = DB::table('product_variants')->inRandomOrder()->limit(15)->pluck('sku')->toArray();

    // Normalize numeric keys and pad to avoid undefined index errors
    $skus = array_values($skus); // reindex

    // Decide how many indexes your banner methods might reference.
    $requiredCount = 13;

    if (count($skus) < $requiredCount) {
      // Use first SKU as fallback if available, else empty string
      $fallback = !empty($skus) ? $skus[0] : '';

      while (count($skus) < $requiredCount) {
        $skus[] = $fallback;
      }
    }

    $url = env('APP_URL') ?: (config('app.url') ?: 'http://localhost');
    $now = now()->toDateTimeString();

    $banners = array_merge(
      $this->getTickerBanners($url, $skus),
      $this->getHeroBanners($url, $skus),
      $this->getHoverCardBanners($skus),
      $this->getBlockWrapBanner($url),
      $this->getBrandCarouselBanners(),
      $this->getAdditionalBanners($url, $skus)
    );

    // Collect any distinct created_by/updated_by ids referenced in banner definitions
    $referencedAdminIds = [];
    foreach ($banners as $b) {
      if (!empty($b['created_by'])) {
        $referencedAdminIds[] = $b['created_by'];
      }
      if (!empty($b['updated_by'])) {
        $referencedAdminIds[] = $b['updated_by'];
      }
    }
    $referencedAdminIds = array_values(array_unique($referencedAdminIds));

    // Ensure those admins exist and get mapping: originalId => actualId in DB
    $adminIdMap = $this->ensureRequiredAdmins($referencedAdminIds);

    // Insert banners mapping created_by/updated_by to actual IDs (or null)
    foreach ($banners as $banner) {
      $createdBy = isset($banner['created_by']) && $banner['created_by'] !== null
        ? ($adminIdMap[$banner['created_by']] ?? null)
        : null;

      $updatedBy = isset($banner['updated_by']) && $banner['updated_by'] !== null
        ? ($adminIdMap[$banner['updated_by']] ?? null)
        : null;

      DB::table('custom_banners')->insert([
        'id' => $banner['id'],
        'title' => $banner['title'],
        'position' => $banner['position'],
        'settings' => json_encode($banner['settings']),
        'custom_order' => $banner['custom_order'] ?? 0,
        'status' => $banner['status'] ?? 1,
        'created_at' => $banner['created_at'] ?? $now,
        'updated_at' => $banner['updated_at'] ?? $now,
        'deleted_at' => $banner['deleted_at'] ?? null,
        'created_by' => $createdBy,
        'updated_by' => $updatedBy,
      ]);
    }
  }

  /**
   * Safely return SKU at index or fallback to empty string.
   */
  private function skuAt(array $skus, int $index): string
  {
    if (array_key_exists($index, $skus) && $skus[$index] !== null) {
      return (string) $skus[$index];
    }

    return '';
  }

  private function getTickerBanners(string $url, array $skus): array
  {
    return [
      ['id' => 1, 'title' => 'Dining Tables starting at $199', 'position' => 'ticker', 'settings' => ['speed' => '30000', 'hyper_link' => $url . '/product/' . $this->skuAt($skus, 0)], 'custom_order' => 6],
      ['id' => 2, 'title' => 'Limited Time Offer: Free throw pillows with sofa purchases', 'position' => 'ticker', 'settings' => ['speed' => '30000', 'hyper_link' => $url . '/product/' . $this->skuAt($skus, 1)], 'custom_order' => 5],
      ['id' => 3, 'title' => 'Free Shipping on orders over $300', 'position' => 'ticker', 'settings' => ['speed' => '30000', 'hyper_link' => $url . '/product/' . $this->skuAt($skus, 2)], 'custom_order' => 2, 'deleted_at' => '2025-05-30 01:18:09'],
      ['id' => 4, 'title' => 'Dining Tables starting at $399', 'position' => 'ticker', 'settings' => ['speed' => '5000', 'hyper_link' => $url . '/product/' . $this->skuAt($skus, 3)], 'custom_order' => 4],
      ['id' => 5, 'title' => 'Free Shipping on orders over $200', 'position' => 'ticker', 'settings' => ['speed' => '5000', 'hyper_link' => $url . '/product/' . $this->skuAt($skus, 4)], 'custom_order' => 3],
      ['id' => 6, 'title' => 'Limited Time Offer: Free throw pillows with sofa purchases', 'position' => 'ticker', 'settings' => ['speed' => '5000', 'hyper_link' => $url . '/product/' . $this->skuAt($skus, 5)], 'custom_order' => 2],
      ['id' => 7, 'title' => 'Free Shipping on orders over $300', 'position' => 'ticker', 'settings' => ['speed' => '30000', 'hyper_link' => $url . '/product/' . $this->skuAt($skus, 6)], 'custom_order' => 1],
    ];
  }

  private function getHeroBanners(string $url, array $skus): array
  {
    return [
      ['id' => 8, 'title' => 'Hero Slider 1', 'position' => 'hero', 'settings' => ['image' => 'homeslider1.webp', 'content' => '<p>Comfort Meets Style<br>in Every Corner of Your Home</p>', 'alt_text' => 'ssdas', 'hyper_link' => $url . '/product/' . $this->skuAt($skus, 7), 'default_image_size' => null], 'custom_order' => 1],
      ['id' => 9, 'title' => 'Hero Slider 2', 'position' => 'hero', 'settings' => ['image' => 'homeslider2.webp', 'content' => '<p>Limited-Time Deal:<br>Up to 40% Off on Sofas</p>', 'alt_text' => 'alt text', 'hyper_link' => $url . '/product/' . $this->skuAt($skus, 8), 'default_image_size' => null], 'custom_order' => 5],
      ['id' => 10, 'title' => 'Hero Slider 3', 'position' => 'hero', 'settings' => ['image' => 'homeslider3.webp', 'content' => '<p>Exclusive Deal:<br>Up to 30% Off on Chairs - Limited Time Only!</p>', 'alt_text' => null, 'hyper_link' => $url . '/product/' . $this->skuAt($skus, 9), 'default_image_size' => null], 'custom_order' => 27],
    ];
  }

  private function getHoverCardBanners(array $skus): array
  {
    return [
      ['id' => 11, 'title' => 'Left Hover Card', 'position' => 'hover_card', 'settings' => ['image' => 'category_threeblocks_th1.webp', 'content' => '<h4 class="font16 fw-normal text-center mb-0">New Arrival</h4><h3 class="font35 fw-normal text-center">Chair</h3>', 'alt_text' => 'ssdas', 'btn_text' => 'View', 'btn_color' => '#42388f', 'hyper_link' => null, 'product_sku' => $this->skuAt($skus, 10), 'default_image_size' => null], 'custom_order' => 6],
      ['id' => 12, 'title' => 'Hover card Middle', 'position' => 'hover_card', 'settings' => ['image' => 'category_threeblocks_th2.webp', 'content' => '<div class="top flow-rootx2 c--blackc"><h4 class="font14 fw-normal text-center mb-0">New Arrival</h4><h3 class="font30 fw-normal text-center">Chair</h3></div><div class="pricebox font20 text-center"><span>$</span>750.00</div>', 'alt_text' => 'alt', 'btn_text' => 'View', 'btn_color' => '#000000', 'hyper_link' => null, 'product_sku' => $this->skuAt($skus, 11), 'default_image_size' => null], 'custom_order' => 8],
      ['id' => 13, 'title' => 'Hover Right', 'position' => 'hover_card', 'settings' => ['image' => 'category_threeblocks_th3.webp', 'content' => '<h4 class="font16 fw-normal text-center mb-0">New Arrival</h4><h3 class="font35 fw-normal text-center">Chair</h3>', 'alt_text' => 'alt', 'btn_text' => 'View', 'btn_color' => '#000000', 'hyper_link' => null, 'product_sku' => $this->skuAt($skus, 12), 'default_image_size' => null], 'custom_order' => 9],
    ];
  }

  private function getBlockWrapBanner(string $url): array
  {
    return [
      ['id' => 14, 'title' => 'Block Wrap', 'position' => 'block_wrap', 'settings' => ['image' => 'sleek-furniture.webp', 'content' => '<p>Blending sleek, contemporary design with artistic forms, our collection enhances every space with sophistication and comfort.</p>', 'alt_text' => 'alt', 'btn_text' => 'View All Collections', 'btn_color' => '#000000', 'hyper_link' => "$url/categories", 'default_image_size' => null], 'custom_order' => 10],
    ];
  }

  private function getBrandCarouselBanners(): array
  {
    $logos = ['brandlogo1.webp', 'brandlogo2.webp', 'brandlogo3.webp', 'brandlogo4.webp', 'brandlogo5.webp', 'brandlogo6.webp', 'brandlogo7.webp', 'brandlogo3.webp', 'brandlogo1.webp', 'brandlogo2.webp'];
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
      ['id' => 25, 'title' => 'Furniture Contemporary 1', 'position' => 'four_hover_cards', 'settings' => ['image' => 'contemporary1.webp', 'content' => '<p>Luxe Comfort Haven</p>', 'alt_text' => 'alt text', 'btn_text' => 'View', 'btn_color' => '#000000', 'hyper_link' => null, 'default_image_size' => null], 'custom_order' => 30],
      ['id' => 26, 'title' => 'Furniture Contemporary 2', 'position' => 'four_hover_cards', 'settings' => ['image' => 'contemporary2.webp', 'content' => '<p>Luxe Comfort Haven</p>', 'alt_text' => 'alt text', 'btn_text' => 'View', 'btn_color' => '#000000', 'hyper_link' => null, 'default_image_size' => null], 'custom_order' => 31],
      ['id' => 27, 'title' => 'Furniture Contemporary 3', 'position' => 'four_hover_cards', 'settings' => ['image' => 'contemporary4.webp', 'content' => '<p>Luxe Comfort Haven</p>', 'alt_text' => 'alt text', 'btn_text' => null, 'btn_color' => '#000000', 'hyper_link' => null, 'default_image_size' => null], 'custom_order' => 32],
      ['id' => 28, 'title' => 'Furniture Contemporary 4', 'position' => 'four_hover_cards', 'settings' => ['image' => 'contemporary5.webp', 'content' => '<p>Luxe Comfort Haven</p>', 'alt_text' => 'alt text', 'btn_text' => 'View', 'btn_color' => '#000000', 'hyper_link' => null, 'default_image_size' => null], 'custom_order' => 33],
      ['id' => 29, 'title' => 'Mayuri Made Beautiful with Functional Elegance', 'position' => 'sale_block_fullwidth', 'settings' => ['image' => 'sales_block_img.webp', 'content' => '<div class="blk"><h2 class="fw-normal c--whitec font80 m-0">SALE</h2><h4 class="fw-normal c--whitec font25">Up to 50% Off</h4></div>', 'alt_text' => null, 'btn_text' => 'View Collections', 'btn_color' => '#000000', 'hyper_link' => "$url/categories", 'default_image_size' => null], 'custom_order' => 34],
      ['id' => 30, 'title' => 'Keep It Flowing', 'position' => 'flow_banner', 'settings' => ['image' => 'furniture__keepfrowing.webp', 'content' => '<p><span>Keep</span> <span>It</span> <span>Flowing</span></p>', 'alt_text' => 'alt text', 'default_image_size' => null], 'custom_order' => 35],
      ['id' => 31, 'title' => 'Subscribe', 'position' => 'subscribe_banner', 'settings' => ['content' => '<p>Subscribe now and save up to 15% on selected orders! Find furniture that perfectly suits your style.</p>'], 'custom_order' => 36],
      ['id' => 32, 'title' => 'Shop Page', 'position' => 'shop_page_banner', 'settings' => ['image' => 'product-hero.webp', 'default_image_size' => null, 'alt_text' => null], 'custom_order' => 6, 'created_by' => 6, 'updated_by' => 6],
      ['id' => 33, 'title' => 'Crafted with Innovation', 'position' => 'category_page_banner', 'settings' => ['image' => 'category_hero.webp', 'default_image_size' => null, 'alt_text' => null, 'content' => '<p class="act font25" data-parallax-strength-vertical="-2.5" data-parallax-height="-2.5"><span data-parallax-target="">Our sofas are durable, simple to assemble,</span><br><span data-parallax-target="">and adaptable to your evolving needs.</span></p>'], 'custom_order' => 6, 'created_by' => 6, 'updated_by' => 6],
      ['id' => 34, 'title' => 'Hotdeal 1', 'position' => 'hot_deals_category_banner', 'settings' => ['image' => 'hot_deals1.webp', 'default_image_size' => null, 'alt_text' => '#', 'hyper_link' => 'https://www.google.com'], 'custom_order' => 6, 'created_by' => 6, 'updated_by' => 6],
      ['id' => 35, 'title' => 'Hotdeal 2', 'position' => 'hot_deals_category_banner', 'settings' => ['image' => 'hot_deals2.webp', 'default_image_size' => null, 'alt_text' => null, 'hyper_link' => null], 'custom_order' => 6, 'created_by' => 6, 'updated_by' => 6],
      ['id' => 36, 'title' => 'Hotdeal 3', 'position' => 'hot_deals_category_banner', 'settings' => ['image' => 'hot_deals3.webp', 'default_image_size' => null, 'alt_text' => null, 'hyper_link' => 'https://www.google.com'], 'custom_order' => 6, 'created_by' => 6, 'updated_by' => 6],
      ['id' => 37, 'title' => 'Hotdeal 4', 'position' => 'hot_deals_category_banner', 'settings' => ['image' => 'hot_deals4.webp', 'default_image_size' => null, 'alt_text' => null, 'hyper_link' => null], 'custom_order' => 6, 'created_by' => 6, 'updated_by' => 6],
      ['id' => 38, 'title' => 'Mayuri Made Beautiful with Functional Elegance', 'position' => 'category_sale_block', 'settings' => ['image' => 'sales_block_img2.webp', 'default_image_size' => null, 'alt_text' => null, 'content' => '<div class="blk"><h2 class="fw-normal c--whitec font80 m-0">SALE</h2><h4 class="fw-normal c--whitec font25">Up to 50% Off</h4></div>', 'btn_text' => 'View Collections', 'btn_color' => '#000000', 'hyper_link' => "$url/categories"], 'custom_order' => 6, 'created_by' => 6, 'updated_by' => 6],
      ['id' => 39, 'title' => 'Luxury', 'position' => 'login_page_banner', 'settings' => ['image' => 'signup_popup_thumb.webp', 'default_image_size' => null, 'alt_text' => null, 'content' => '<p>Discover 30k+ varieties</p>'], 'custom_order' => 6, 'created_by' => 6, 'updated_by' => 6],
      ['id' => 40, 'title' => 'Ingenious Design Meets Lasting Durability', 'position' => 'category_page_headline_banner', 'settings' => [], 'custom_order' => 6, 'created_by' => 6, 'updated_by' => 6],
      ['id' => 41, 'title' => '', 'position' => 'app_splash_logo', 'settings' => ['image' => 'app_splash_logo.webp', 'alt_text' => 'alt', 'bg_color' => '#2fabca', 'default_image_size' => null], 'custom_order' => 5, 'created_by' => 5, 'updated_by' => 5],
      ['id' => 42, 'title' => 'App Journey Begins', 'position' => 'app_journey_screen', 'settings' => ['image' => 'app_journey_screen.webp', 'alt_text' => 'alt', 'btn_text' => 'Explore Now', 'btn_color' => '#000000', 'show_button' => true, 'skip_btn_text' => 'Skip', 'skip_btn_color' => '#000000', 'show_skip_button' => true, 'default_image_size' => null], 'custom_order' => 5, 'created_by' => 5, 'updated_by' => 5],
      ['id' => 43, 'title' => '', 'position' => 'app_category_page_checkout_collections', 'settings' => ['image' => 'app_category_page_checkout_collections1.webp', 'alt_text' => 'alt text', 'hyper_link' => 'https://www.yahoo.com/', 'banner_type' => null, 'default_image_size' => null], 'custom_order' => 5, 'created_by' => 5, 'updated_by' => 5],
      ['id' => 44, 'title' => '', 'position' => 'app_category_page_checkout_collections', 'settings' => ['image' => 'app_category_page_checkout_collections2.webp', 'alt_text' => 'alt text', 'hyper_link' => 'https://www.yahoo.com/', 'banner_type' => null, 'default_image_size' => null], 'custom_order' => 5, 'created_by' => 5, 'updated_by' => 5],
      ['id' => 45, 'title' => '', 'position' => 'app_category_page_checkout_collections', 'settings' => ['image' => 'app_category_page_checkout_collections3.webp', 'alt_text' => 'alt text', 'hyper_link' => 'https://www.yahoo.com/', 'banner_type' => null, 'default_image_size' => null], 'custom_order' => 5, 'created_by' => 5, 'updated_by' => 5],
      ['id' => 46, 'title' => '', 'position' => 'four_hover_card_title', 'settings' => ['title' => 'Contemporary'], 'custom_order' => 1],
      ['id' => 47, 'title' => '', 'position' => 'app_home_landing_inner_banner', 'settings' => ['image' => 'home_sub_banner_1.webp', 'alt_text' => 'alt text', 'hyper_link' => 'https://www.yahoo.com/', 'banner_type' => null, 'default_image_size' => null], 'custom_order' => 5],
      ['id' => 48, 'title' => '', 'position' => 'app_home_landing_inner_banner', 'settings' => ['image' => 'home_sub_banner_2.webp', 'alt_text' => 'alt text', 'hyper_link' => 'https://www.google.com/', 'banner_type' => null, 'default_image_size' => null], 'custom_order' => 1],
      ['id' => 49, 'title' => '', 'position' => 'app_home_landing_inner_banner', 'settings' => ['image' => 'home_sub_banner_3.webp', 'alt_text' => 'alt text', 'hyper_link' => 'https://www.yahoo.com/', 'banner_type' => null, 'default_image_size' => null], 'custom_order' => 2],
      ['id' => 50, 'title' => '', 'position' => 'home_page_top_category_banner', 'settings' => ['options' => [3, 6, 9, 20], 'banner_type' => null], 'custom_order' => 6, 'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
    ];
  }


  private function uploadBannerImages()
  {
    $relativePath    = 'uploads/banners';
    $disk            = Storage::disk('public');
    $destinationPath = storage_path("app/public/{$relativePath}");
    $sourcePath      = public_path('SeederImages/Fashion/banners');

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
