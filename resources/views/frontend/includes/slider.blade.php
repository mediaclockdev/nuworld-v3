@props([
    'heading' => 'Collections',
    'options' => true,
    'slug' => '',
    'collections' => [],
])
<section class="furniture__products_scroller_wrap flow-rootX3">
  <div class="container-xxl">
    <div class="row">
      <div class="col-lg-4">
        <h2 class="fw-normal m-0 font45 c--blackc">{{ $heading }}</h2>
      </div>
      @if ($options)
        <div class="col-lg-8">
          <div class="filterswrap gap-4 d-flex align-items-center justify-content-end">
            <div class="filterblocks d-flex gap-4">
              <a href="#" class="group-filter active" data-category="all">All</a>
              @foreach ($collections as $index => $collection)
                <a href="#" class="group-filter "
                  data-category="{{ $collection->slug }}">{{ $collection->title }}</a>
              @endforeach
            </div>
            {{-- @if ($slug)
              <a href="{{ route($slugRoute, $slug) }}" class="btn btn-outline-dark" title="View Collections">View
                Collections</a>
            @else
              <a href="{{ route($listRoute) }}" class="btn btn-outline-dark" title="View Collections">View
                Collections</a>
            @endif --}}

            @if ($slug)
              <a href="#" class="btn btn-outline-dark" title="View Collections">View
                Collections</a>
            @else
              <a href="#" class="btn btn-outline-dark" title="View Collections">View
                Collections</a>
            @endif
          </div>
        </div>
      @endif
    </div>
  </div>
  <div class="container-xxl">
    <div class="swiperwrp">
      <div class="swiper swiper__new">
        <div class="swiper-wrapper eq-height" id="product-scroller">
        </div>
      </div>
      {{-- <div class="swiper-nav-inline">
        <div class="swipper_button swiper__new--prev"><span
            class="material-symbols-outlined font35 c--blackc">arrow_back_ios_new</span></div>
        <div class="swipper_button swiper__new--next"><span
            class="material-symbols-outlined font35 c--blackc">arrow_forward_ios</span></div>
      </div> --}}
      <div class="error-message" style="display:none;color:#dc3545;text-align:center;margin-top:1rem;"></div>
    </div>
  </div>
</section>

@push('styles')
  <style>
    #product-scroller.loading-overlay {
      position: relative;
      min-height: 300px;
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
    }

    .product-card.loading {
      position: relative;
      opacity: 0.7;
    }

    .product-card.loading::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(255, 255, 255, 0.7);
      z-index: 10;
    }

    .group-filter.active {
      color: #000;
      text-decoration: underline;
    }
  </style>
@endpush

@push('scripts')
  <script>
    (function($) {
      const config = {
        urls: {
          collections: '{{ route('home.collections') }}',
          variantDetails: '{{ route('home.getVariantDetails') }}'
        },
        defaultCategory: 'all'
      };
      let swiper;

      function initSwiper() {
        const $wrapper = $('#product-scroller');
        const totalSlides = $wrapper.find('.swiper-slide').length;

        const breakpoints = {
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
            slidesPerView: 4.5
          }
        };

        const slidesPerView = Object.keys(breakpoints).reduce(
          (acc, bp) => window.innerWidth >= bp ? breakpoints[bp].slidesPerView : acc,
          4
        );

        const enableSwiper = totalSlides > slidesPerView;
        // const enableSwiper = false;
        if (swiper) {
          console.log('Destroying existing swiper');
          swiper.destroy(true, true);
          swiper = null; // clear reference to avoid reusing destroyed instance
        }

        swiper = new Swiper('.swiper__new', {
          loop: enableSwiper,
          slidesPerView,
          spaceBetween: 20,
          centeredSlides: false,
          allowTouchMove: enableSwiper,
          navigation: enableSwiper ? {
            nextEl: '.swiper__new--next',
            prevEl: '.swiper__new--prev'
          } : false,
          breakpoints,
          autoplay: enableSwiper ? {
            delay: 5000,
            disableOnInteraction: false, // <--- Important for resume after hover
            pauseOnMouseEnter: true // <--- We'll control it manually
          } : false
        });

        $('.swiper-nav-inline').toggle(enableSwiper);

        if (enableSwiper && swiper.autoplay) {
          $('.swiper__new .swiper-slide').off('mouseenter mouseleave').
          on('mouseenter', function() {
            console.log('Hovered: stopping autoplay');
            swiper.autoplay.stop();
          }).on('mouseleave', function() {
            swiper.autoplay.start();
          });
        }
      }

      function fetchProducts(categorySlug) {
        const $wrapper = $('#product-scroller').addClass('loading-overlay');
        const $error = $('.error-message').hide();
        swiper && (swiper.allowSlideNext = swiper.allowSlidePrev = swiper.allowTouchMove = false);

        $.get(config.urls.collections, {
            category_slug: categorySlug,
            excludeProductId: `{{ isset($productVariant) ? Hashids::encode($productVariant->product_id) : '' }}`,
          })
          .done(response => {
            $wrapper.empty().append(response);
            initSwiper();
          })
          .fail(() => $error.text('Failed to load products. Try again.').show())
          .always(() => {
            $wrapper.removeClass('loading-overlay');
            swiper && (swiper.allowSlideNext = swiper.allowSlidePrev = swiper.allowTouchMove = true);
          });

      }

      class ProductCardUpdater {
        constructor($card) {
          this.$card = $card;
        }

        update(variant) {
          const {
            name,
            url,
            value,
            image,
            image_name,
            sale_price,
            regular_price,
            discount_percent,
            isDiscount,
            in_cart,
            isOutOfStock
          } = variant;

          // --- Remove previous badges and price ---
          this.$card.find('.tag.out-of-stock').remove();
          this.$card.find('.tag.primary').remove();
          this.$card.find('.price').empty();

          const $showingbag = this.$card.find('.showingbag');
          const $btn = $showingbag.find('a.add-to-cart-btn');

          // --- Remove any old loader ---
          this.$card.find('.dot-loader-btn').remove();

          // --- Decide showingbag visibility first ---
          if (isOutOfStock || in_cart) {
            $showingbag.addClass('d-none');
          } else {
            $showingbag.removeClass('d-none');
            // Restore proper button markup
            $btn.prop('disabled', false)
              .html('<span class="material-symbols-outlined">local_mall</span>')
              .attr('title', 'Add To Cart')
              .css('display', '')
              .show();
          }

          // --- Basic updates ---
          this.$card.find('.product_name h3 a').text(name);
          this.$card.find('.details_link').attr('href', url);
          this.$card.find('form input[name="product_variant_id"]').val(value);
          $btn.attr('data-id', value);
          this.$card.find('.product_variant_id').text(value);

          this.$card.find('img.product-image').attr({
            alt: image_name,
            src: image
          }).addClass('active');

          // --- Out of stock badge ---
          if (isOutOfStock) {
            this.$card.find('.main-image-wrap').prepend(`
        <div class="tag out-of-stock font12" style="background-color: #dc3545; color: #fff;">Out of Stock</div>
      `);
            this.$card.find('.price').html(`<span class="text-danger">Currently Unavailable</span>`);
          }

          // --- Price update ---
          if (isDiscount && sale_price) {
            this.$card.find('.price').html(`
        ${sale_price}<span class="old-price ms-2">${regular_price}</span>

      `);
            this.$card.find('.main-image-wrap').prepend(`
        <div class="tag primary font12">${discount_percent} Off</div>
      `);
          } else {
            this.$card.find('.price').html(regular_price || sale_price || '');
          }
          if (variant.special_price) {
            this.$card.find('.price').append(`
    <span class="special-offer-badge-small">Special Offer</span>
  `);
          }
        }
      }




      $(document).ready(() => {
        initSwiper();
        fetchProducts(config.defaultCategory);

        $('.group-filter').click(function(e) {
          e.preventDefault();
          $('.group-filter').removeClass('active').filter(this).addClass('active');
          fetchProducts($(this).data('category') || 'all');
        });

        $(window).resize(initSwiper);

        $(document).on('click', '.color-option .circles .circle', async function() {
          const $circle = $(this);
          const $switcher = $circle.parent();
          const $card = $circle.closest('.product-card').addClass('loading');

          const variantId = $switcher.data('variant-id');
          const color = $circle.data('color');
          const hex = $circle.data('hex');

          $switcher.find('.circle').removeClass('active').css('box-shadow', '');
          $circle.addClass('active').css('box-shadow', `0 0 0 2px #fff, 0 0 0 3px ${hex}`);
          $card.find('img').attr('src', '');

          try {
            const {
              success,
              variant
            } = await $.ajax({
              url: config.urls.variantDetails,
              method: 'GET',
              data: {
                variant_id: variantId,
                color
              }
            });

            if (success) new ProductCardUpdater($card).update(variant);
            else $('.error-message').text('Failed to load variant.').show();
          } catch {
            $('.error-message').text('Failed to load variant. Please try again.').show();
          } finally {
            $card.removeClass('loading');
          }
        });
      });
    })(jQuery);
  </script>
@endpush
