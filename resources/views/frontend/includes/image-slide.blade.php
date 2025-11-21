<div class="product-details-slider-wrap">
  <div class="product-details-slider flow-rootX">
    <div
      class="swiper swiper--product-detail swiper-initialized swiper-horizontal swiper-watch-progress swiper-backface-hidden">
      <div class="swiper-wrapper" id="swiper-wrapper-e9eeeecb559dc8e8" aria-live="polite"
        style="transition-duration: 0ms; transform: translate3d(0px, 0px, 0px);">
        @forelse ($orderedImages as $item)
          <div class="swiper-slide swiper-slide-visible swiper-slide-active" role="group">
            <div class="easyzoom easyzoom--overlay">
              <a href="javascript:void(0)" class="ratio ratio-1000x600">
                @if ($item->gallery->file_type == 'video/mp4')
                  <video width="100%" height="100%" controls>
                    <source
                      src="{{ asset('public/storage/uploads/media/products/videos/' . $item->gallery->file_name) }}"
                      type="video/mp4">
                  </video>
                @else
                  <img src="{{ asset('public/storage/uploads/media/products/images/' . $item->gallery->file_name) }}"
                    alt="{{ $productVariant->name }}" title="{{ $productVariant->name }}">
                @endif
              </a>
            </div>
          </div>
        @empty
          <div class="swiper-slide">
            <div class="ratio ratio-1000x800 border">
              <img src="{{ asset('public/backend/assetss/images/products/product_thumb.jpg') }}" alt=""
                title="" />
            </div>
          </div>
        @endforelse
      </div>
      <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
    </div>

    {{-- <div
      class="swiper swiper--product-thumbs swiper-initialized swiper-horizontal swiper-free-mode swiper-backface-hidden swiper-thumbs">
      <div class="swiper-wrapper" id="swiper-wrapper-e852b1cda78ac8be" aria-live="polite"
        style="transform: translate3d(0px, 0px, 0px); transition-duration: 0ms;">
        @forelse ($orderedImages as $item)
          <div class="swiper-slide swiper-slide-visible swiper-slide-active swiper-slide-thumb-active"
            style="width: 204.8px; margin-right: 10px;" role="group" aria-label="1 / 7">
            <div class="ratio ratio-1000x800 border">
              @if ($item->gallery->file_type == 'video/mp4')
                <video src="{{ asset('public/storage/uploads/media/products/videos/' . $item->gallery->file_name) }}"
                  controls title="{{ $productVariant->name }}"></video>
              @elseif (
                  $item->gallery->file_type == 'image/jpeg' ||
                      $item->gallery->file_type == 'image/png' ||
                      $item->gallery->file_type == 'image/jpg' ||
                      $item->gallery->file_type == 'image/webp')
                <img src="{{ asset('public/storage/uploads/media/products/images/' . $item->gallery->file_name) }}"
                  alt="{{ $productVariant->name }}" title="{{ $productVariant->name }}">
              @endif
            </div>
          </div>
        @empty
          <div class="swiper-slide">
            <div class="ratio ratio-1000x800 border">
              <img src="{{ asset('public/backend/assetss/images/products/product_thumb.jpg') }}" alt=""
                title="" />
            </div>
          </div>
        @endforelse
      </div>
      <div class="swiper-scrollbar swiper-scrollbar-horizontal" style="opacity: 0; transition-duration: 400ms;">
        <div class="swiper-scrollbar-drag"
          style="transform: translate3d(0px, 0px, 0px); transition-duration: 0ms; width: 0px;"></div>
      </div>
      <div class="swiper-buttons">
        <div class="swiper-button-prev swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide"
          aria-controls="swiper-wrapper-e852b1cda78ac8be" aria-disabled="true">
        </div>
        <div class="swiper-button-next" tabindex="0" role="button" aria-label="Next slide"
          aria-controls="swiper-wrapper-e852b1cda78ac8be" aria-disabled="false"></div>
      </div>
      <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
    </div> --}}
  </div>
</div>
