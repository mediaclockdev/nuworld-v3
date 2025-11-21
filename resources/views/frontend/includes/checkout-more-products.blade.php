{{-- @php
  pd($productVariant);
@endphp --}}
<section class="furniture__products_scroller_wrap pb-5">
  <div class="container-xxl flow-rootX3">
    <h2 class="fw-normal m-0 font45 c--blackc">Check Out More Products</h2>
    <div class="swiperwrp">
      <div class="swiper swiper__view_more">
        <div class="swiper-wrapper eq-height" id="product-scroller">
        </div>
      </div>
      <div class="swiper-nav-inline">
        <div class="swipper_button swiper__view_more--prev"><span
            class="material-symbols-outlined font35 c--blackc">arrow_back_ios_new</span></div>
        <div class="swipper_button swiper__view_more--next"><span
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
        defaultCategory: '{{ $productVariant->category->slug ?? '' }}'
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
            slidesPerView: 4
          }
        };

        const slidesPerView = Object.keys(breakpoints).reduce(
          (acc, bp) => window.innerWidth >= bp ? breakpoints[bp].slidesPerView : acc,
          4
        );

        const enableSwiper = totalSlides > slidesPerView;
        if (window.swiper) {
          swiper.destroy(true, true);
        }

        swiper = new Swiper('.swiper__view_more', {
          loop: enableSwiper,
          slidesPerView,
          spaceBetween: 20,
          allowTouchMove: enableSwiper,
          navigation: enableSwiper ? {
            nextEl: '.swiper__view_more--next',
            prevEl: '.swiper__view_more--prev'
          } : false,
          breakpoints,
          autoplay: enableSwiper ? {
            delay: 5000,
            disableOnInteraction: false, // <--- Important for resume after hover
            pauseOnMouseEnter: false // <--- We'll control it manually
          } : false
        });

        $('.swiper-nav-inline').toggle(enableSwiper);

        if (enableSwiper && swiper.autoplay) {
          $('.swiper__view_more .swiper-slide').off('mouseenter mouseleave').
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
            excludeProductId: `{{ $productVariant->product_id ?? '' }}`,
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
            in_cart
          } = variant;

          this.$card.find('.product_name h3 a').text(name);
          this.$card.find('.details_link').attr('href', url);
          this.$card.find('form input[name="product_variant_id"]').val(value);
          this.$card.find('.showingbag a.add-to-cart-btn').attr('data-id', value);

          if (in_cart) {
            this.$card.find('.showingbag').addClass('d-none');
            this.$card.find('.showingbag a.add-to-cart-btn').css('display', 'none');
          } else {
            this.$card.find('.showingbag').removeClass('d-none');
            this.$card.find('.showingbag a.add-to-cart-btn').removeAttr('style');
          }

          this.$card.find('.product_variant_id').text(value);

          this.$card.find('img.product-image').attr({
            alt: image_name,
            src: image
          }).addClass('active');

          // Display both prices if discount is present
          const priceHtml = isDiscount ?
            `${sale_price}<span class="old-price ms-2">${regular_price}</span>` :
            sale_price || regular_price;

          this.$card.find('.price').html(priceHtml);

          // Handle discount badge
          const $tag = this.$card.find('.tag.primary');
          if (isDiscount && discount_percent) {
            const badgeText = `${discount_percent} Off`; // already includes '%'
            if ($tag.length) {
              $tag.text(badgeText);
            } else {
              this.$card.find('.main-image-wrap').prepend(
                `<div class="tag primary font12">${badgeText}</div>`
              );
            }
          } else {
            $tag.remove();
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
