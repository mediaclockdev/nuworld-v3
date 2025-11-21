@php
  $settings = [];
  if (!empty($productListingBanner) && isset($productListingBanner->settings)) {
      $settings = json_decode($productListingBanner->settings, true);
  }
  //$settings = json_decode($keepFlowing->settings, true);
@endphp

<section class="category_hero fullBG mt-0 p-0">
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-lg-12">
        <figure class="m-0"><img
            src="{{ !empty($settings['image']) ? asset(config('defaults.banner_image_path') . $settings['image']) : asset('public/frontend/assets/img/home/search_banner.jpg') }}"
            alt="{{ $settings['alt_text'] ?? '' }}" />
        </figure>
      </div>
    </div>
  </div>
</section>
