<div class="card h-100 shadow-sm" style="border-radius: 12px;">
  <div class="card-body d-flex flex-column">
    <div class="d-flex align-items-center mb-3">
      <h5 class="card-title mb-0">{{ $value['display_name'] }}</h5>
      <span class="badge bg-warning ms-2">
        {{ isset($customBanners[$key]) ? count($customBanners[$key]) : 0 }}
      </span>
      <a href="{{ route('admin.banners.edit-banner', ['key' => $key]) }}" class="btn btn-sm btn-primary ms-auto">Edit</a>
    </div>

    <div class="d-flex flex-wrap gap-2 align-items-start">
      @php $hasImage = false; @endphp

      @if (!empty($customBanners[$key]))
        @foreach ($customBanners[$key] as $banner)
          @php
            $settings = json_decode($banner['settings'], true);
            $image = $settings['image'] ?? null;
            if ($image) {
                $hasImage = true;
            }
          @endphp

          @if ($image)
            <img src="{{ asset("public/storage/uploads/banners/$image") }}" alt="" class="banner-thumbnail">
          @endif
        @endforeach
      @endif

      @if (!$hasImage)
        @php
          $staticImages = [
              'Ticker' => 'ticker.png',
              'Sale Block Full Width' => 'sale_block.png',
              'Subscribe Banner' => 'subscribe.png',
              'Hero Slider' => 'banner.png',
              'Hover Card' => 'hover_card.png',
              'Block Wrap' => 'block-wrap.png',
              'Brand Carousel' => 'brand.png',
              'Four Hover Cards' => '4-hover-card.png',
              'Keep Flowing Banner' => 'keep-flowing.png',
              'Shop Page Banner' => 'shop_page_banner.png',
              'Category page banner' => 'category_page_banner.png',
              'Category Sale Block' => 'category_sale_block.png',
              'Hot Deals Category Banner' => 'category_deals.png',
              'Login Page Banner' => 'login_banner.png',
              'Category Page Headline Banner' => 'category_page_headline.png',
              'App Splash Logo Banner' => 'app_intro.png',
              'App Journey Screen Banner' => 'app_splash.png',
              'App Category Page Checkout Collections' => 'category_page_app_collections.png',
              'App Home Landing Inner Banner' => 'app_home_landing_inner.png',
              'Home Page Top Category Banner' => 'home_page_top_category_banner.png',
              'Blog Page Banner' => 'blog_page_banner.png',
              'Book Collection Banner' => 'book_collection_banner.png',
              'Mega Menu Banner' => 'mega_menu_banner.png',
              'Shop Details Page Banner' => 'shop_details.png',
              'Home Page Trending Products Banner' => 'home_page_top_category_banner.png',
          ];
          $fallback = $staticImages[$value['display_name']] ?? null;
        @endphp

        @if ($fallback)
          <img src="{{ asset("public/backend/assetss/images/static_images/$fallback") }}" alt="Fallback"
            class="fallback-banner">
        @endif
      @endif
    </div>
  </div>
</div>
