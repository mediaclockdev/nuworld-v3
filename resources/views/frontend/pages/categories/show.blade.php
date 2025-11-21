@extends('frontend.layouts.app')

@section('title', @$title)
@section('content')
  {{-- @include('frontend.includes.listing-banner') --}}
  {{-- @php
    pd($productListingBanner);
  @endphp --}}
  @php
    //pd($category_slug);
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
          @if ($category_slug == 'chicken')
            <figure class="m-0"><img src="{{ asset('public\frontend\assets\img\home\chicken_old.png') }}"
                alt="" />
            </figure>
          @else
            <figure class="m-0"><img
                src="{{ !empty($settings['image']) ? asset(config('defaults.banner_image_path') . $settings['image']) : asset('public\SeederImages\Grocery\banners\g-product-hero.webp') }}"
                alt="{{ $settings['alt_text'] ?? '' }}" />
            </figure>
          @endif
        </div>
      </div>
    </div>
  </section>
  <section class="furniture__product_listingwrp">
    <div class="container-xxl flow-rootX3">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="font45 fw-normal c--blackc">{{ $title ?? '' }}
          </h1>
          {{-- <p class="font18">Found: <span class="fw-bold">{{ $totalVariants }}</span> items</p> --}}
        </div>
      </div>
      @include('frontend.includes.list-top-slider')
      @include('frontend.includes.filter-buttons')
      <div class="row">
        <div class="col-lg-12">
          <div class="furniture--grid-4 y-axis mt-3">
            @include('frontend.includes.product-card')
          </div>
        </div>
        <div class="mt-4">
          {{ $variants->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
      </div>
    </div>
  </section>
@endsection
