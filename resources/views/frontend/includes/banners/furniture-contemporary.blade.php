<section class="furniture__contemporary_wrap">
  <div class="container-xxl">
    <div class="row">
      <div class="col-lg-12">
        @php

          if (!empty($homePageFourHovercaedTitle['settings'])) {
              $titleSettings = json_decode($homePageFourHovercaedTitle['settings'], true);
              $title = isset($titleSettings['title']) ? $titleSettings['title'] : 'Contemporary';
          }
        @endphp
        <div class="headdings font100 c--black">{{ $title }}</div>

        <div class="swiperwrp">
          <div class="swiper swiper__3">
            <div class="swiper-wrapper eq-height">
              @forelse ($furnitureContemporaries as $furnitureContemporary)
                @php
                  $banner = $furnitureContemporary['furnitureContemporaryBaner'];
                  $variant = $furnitureContemporary['furnitureContemporaryProductVariant'];
                  $settings = json_decode($banner['settings'] ?? '{}', true);
                @endphp

                <div class="swiper-slide">
                  <div class="contemporary-card">
                    <div class="card-wrp">
                      <figure class="m-0 grayscale-hover">
                        <img
                          src="{{ !empty($settings['image']) ? asset(config('defaults.banner_image_path') . $settings['image']) : asset('public/frontend/assets/img/home/contemporary1.jpg') }}"
                          alt="{{ $settings['alt_text'] ?? '' }}" class="imageFit" />
                      </figure>
                    </div>

                    <div class="bagwrap">
                      @if ($variant)
                        <form id="add-to-cart-form-{{ $variant->id }}" action="{{ route('cart.add') }}" method="POST"
                          style="display: none;">
                          @csrf
                          <input type="hidden" name="product_variant_id" value="{{ $variant->id }}">
                          <input type="hidden" name="quantity" value="1">
                          <input type="hidden" name="is_saved_for_later" value="0">
                          <input type="hidden" name="action" value="add_to_cart">
                        </form>

                        <div class="{{ isInCart($variant->id) ? 'd-none' : 'showingbag' }}">
                          <a href="javascript:void(0)" class="{{ isInCart($variant->id) ? '' : 'add-to-cart-btn' }}"
                            data-id="{{ $variant->id }}" title="Add To Cart"
                            @if (isInCart($variant->id)) style="pointer-events: none; opacity: 0.4;" @endif>
                            <span class="material-symbols-outlined">local_mall</span>
                          </a>
                        </div>
                      @endif

                      <div class="productinfo">
                        @if ($variant)
                          {{ $variant->name }}
                        @else
                          {!! $settings['content'] ?? '' !!}
                        @endif

                        <a
                          href="{{ $settings['hyper_link'] ?? ($variant && $variant->sku ? route('product.show', $variant->sku) : '#') }}">
                          {{ $settings['btn_text'] ?? 'Shop Now' }}
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              @empty
                {{-- Optional: Display a message when there's no data --}}
              @endforelse
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- <div class="container">
    <div class="row">
      <div class="col-lg-2 ms-auto">
        <div class="swiper-nav-inline">
          <div class="swiper__3-pagination me-3 w-auto"></div>
          <div class="swipper_button swiper__3--prev">
            <span class="material-symbols-outlined font35 c--blackc">arrow_back_ios_new</span>
          </div>
          <div class="swipper_button swiper__3--next">
            <span class="material-symbols-outlined font35 c--blackc">arrow_forward_ios</span>
          </div>
        </div>
      </div>
    </div>
  </div> --}}
</section>


@push('styles')
  <style>
    /* .grayscale-hover img {
        transition: filter 0.3s ease;
      } */

    /* .grayscale-hover:hover img {
        filter: grayscale(100%);
      } */

    /* .contemporary-card .card-wrp figure:last-child {
        opacity: 1;
      } */

    .swiper__3-pagination {
      display: none !important;
    }
  </style>
@endpush
