<section class="furniture__products_scroller_wrap flow-rootX3">
  <div class="container-xxl">
    <div class="row">
      <div class="col-lg-4">
        <h2 class="fw-normal m-0 font45 c--blackc">Collections</h2>
      </div>

      <div class="col-lg-8">
        <div class="filterswrap gap-4 d-flex align-items-center justify-content-end">
          <div class="filterblocks d-flex gap-4">
            <a href="#" class="group-filter active">All</a>
            @foreach ($productCategories as $category)
              <a href="#" class="group-filter" data-category="{{ $category->slug }}">
                {{ $category->title }}
              </a>
            @endforeach
          </div>
          <a href="{{ route('category.list') }}" class="btn btn-outline-dark" title="View Collections">View
            Collections</a>
        </div>
      </div>
    </div>
  </div>

  <div class="container-xxl">
    <div class="swiperwrp">
      <div class="swiper swiper__2">
        <div class="swiper-wrapper eq-height" id="product-scroller">
        </div>
      </div>
      <div class="swiper-nav-inline">
        <div class="swipper_button swiper__2--prev"><span
            class="material-symbols-outlined font35 c--blackc">arrow_back_ios_new</span></div>
        <div class="swipper_button swiper__2--next"><span
            class="material-symbols-outlined font35 c--blackc">arrow_forward_ios</span></div>
      </div>
    </div>
  </div>
</section>

@push('styles')
  <style>
    #product-scroller.loading-overlay {
      position: relative;
      min-height: 300px;
      /* Adjust based on your content height */
    }

    #product-scroller.loading-overlay::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(255, 255, 255, 0.7);
      z-index: 20;
      pointer-events: all;
      /* cursor: wait; */
    }
  </style>
@endpush

@push('scripts')
  <script>
    let swiperInstance;

    function initSwiper() {
      const totalSlides = document.querySelectorAll('#product-scroller .swiper-slide').length;

      const breakpointsConfig = {
        0: {
          slidesPerView: 1.2
        },
        768: {
          slidesPerView: 2.5
        },
        992: {
          slidesPerView: 3
        },
        1200: {
          slidesPerView: 4
        }
      };

      const defaultSlidesPerView = 4; // fallback

      // Get actual slidesPerView based on current window width
      let currentSlidesPerView = defaultSlidesPerView;
      const width = window.innerWidth;
      for (const bp in breakpointsConfig) {
        if (width >= bp) {
          currentSlidesPerView = breakpointsConfig[bp].slidesPerView;
        }
      }

      const shouldEnableSwiper = totalSlides > currentSlidesPerView;

      swiperInstance = new Swiper(".swiper__2", {
        loop: shouldEnableSwiper,
        slidesPerView: currentSlidesPerView,
        spaceBetween: 20,
        allowTouchMove: shouldEnableSwiper,
        navigation: shouldEnableSwiper ? {
          nextEl: ".swiper__2--next",
          prevEl: ".swiper__2--prev",
        } : false,
        breakpoints: breakpointsConfig,
      });

      // Optionally hide navigation arrows if not needed
      const navWrapper = document.querySelector(".swiper-nav-inline");
      if (!shouldEnableSwiper) {
        navWrapper.style.display = "none";
      } else {
        navWrapper.style.display = "flex";
      }
    }

    function destroySwiper() {
      if (swiperInstance) {
        swiperInstance.destroy(true, true);
      }
    }

    function fetchFilteredProducts(categorySlug = 'all') {
      destroySwiper();
      initSwiper();

      const $wrapper = $('#product-scroller');
      $wrapper.addClass('loading-overlay');

      // Disable Swiper interaction
      if (swiperInstance) {
        swiperInstance.allowSlideNext = false;
        swiperInstance.allowSlidePrev = false;
        swiperInstance.allowTouchMove = false;
      }

      $.ajax({
        url: "{{ route('home.collections') }}",
        method: 'GET',
        data: {
          category_slug: categorySlug
        },
        success: function(response) {
          $wrapper.empty();
          $wrapper.append(response);

          destroySwiper();
          initSwiper();
        },
        error: function() {
          console.log('Failed to load collection.');
        },
        complete: function() {
          $wrapper.removeClass('loading-overlay');

          // Re-enable Swiper interaction
          if (swiperInstance) {
            swiperInstance.allowSlideNext = true;
            swiperInstance.allowSlidePrev = true;
            swiperInstance.allowTouchMove = true;
          }
        }
      });
    }

    $(window).on('resize', function() {
      destroySwiper();
      initSwiper();
    });

    $(document).ready(function() {
      initSwiper();
      fetchFilteredProducts();

      $('.group-filter').on('click', function(e) {
        e.preventDefault();

        let categorySlug = $(this).data('category') || 'all';

        $('.group-filter').removeClass('active');
        $(this).addClass('active');

        fetchFilteredProducts(categorySlug);
      });
    });
  </script>
@endpush
