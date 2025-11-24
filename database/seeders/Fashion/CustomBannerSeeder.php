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
