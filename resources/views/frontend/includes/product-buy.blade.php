<div class="product-details-content-info flow-rootX2">
  <div>
    <h3 class="product-title font30 fw-normal">{{ $productVariant->name }}</h3>
    <p class="font14 d-flex gap-3">
      {{ $productVariant->product->description }}
    </p>
  </div>
  <div class="ratings d-flex align-items-center gap-2">
    @php
      $fullStars = $totalRatings > 0 ? floor($averageRating) : 0;
      $halfStar = $totalRatings > 0 && $averageRating - $fullStars >= 0.5;
      $emptyStars = 5;
      $rating = $productVariant->variantReview->rating ?? 0;
      $isEdit = isset($productVariant->variantReview->id);
      $defaultImage = $productVariant->images[0]->gallery->file_name ?? null;
    @endphp

    <span class="font18">{{ $totalRatings > 0 ? number_format($averageRating, 1) : '' }}</span>

    @if ($averageRating > 0)
      {{-- Show only one filled star when rating exists --}}
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="bi bi-star-fill" viewBox="0 0 16 16"
        fill="#F69029">
        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173
    6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927
    0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522
    3.356.83 4.73c.078.443-.36.79-.746.592L8
    13.187l-4.389 2.256z" />
      </svg>
    @else
      {{-- Show 5 empty stars when no rating --}}
      @for ($i = 0; $i < $emptyStars; $i++)
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ddd" viewBox="0 0 16 16">
          <path d="M2.866 14.85c-.078.444.36.791.746.593L8
      13.187l4.389 2.256c.386.198.824-.149.746-.592
      l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95
      l-4.898-.696L8.465.792a.513.513 0 0 0-.927
      0L5.354 5.12l-4.898.695c-.441.062-.612.636-.283.95
      l3.522 3.356-.83 4.73z" />
        </svg>
      @endfor
    @endif

    {{-- Full Stars --}}
    {{-- @for ($i = 0; $i < $fullStars; $i++)
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#F69029" viewBox="0 0 16 16">
        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95
          l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327
          4.898.696c.441.062.612.636.282.95l-3.522 3.356
          .83 4.73c.078.443-.36.79-.746.592L8
          13.187l-4.389 2.256z" />
      </svg>
    @endfor --}}

    {{-- Half Star --}}
    {{-- @if ($halfStar)
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#F69029" viewBox="0 0 16 16">
        <path d="M8 0c-.27 0-.52.15-.65.39L5.54 4.72l-4.9.7c-.44.06-.61.64-.28.95l3.52
          3.36-.83 4.73c-.08.44.36.79.75.59L8
          13.19V0z" />
      </svg>
    @endif --}}

    {{-- Empty Stars --}}
    {{-- @for ($i = 0; $i < $emptyStars; $i++)
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ddd" viewBox="0 0 16 16">
        <path d="M2.866 14.85c-.078.444.36.791.746.593L8
          13.187l4.389 2.256c.386.198.824-.149.746-.592
          l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95
          l-4.898-.696L8.465.792a.513.513 0 0 0-.927
          0L5.354 5.12l-4.898.695c-.441.062-.612.636-.283.95
          l3.522 3.356-.83 4.73z" />
      </svg>
    @endfor --}}


    <span class="font18 border-left">{{ $totalRatings }} Ratings</span>
    @if ($totalRatings > 0)
      <a href="#allReviews" class="font14">View All Review</a>
    @endif

    {{-- @auth
      @if ($hasCompletedOrder)
        <a href="javascript:void(0);" class="action_addreview d-flex align-items-center gap-2 open-review-modal"
          data-bs-toggle="modal" data-bs-target="#WriteReviewModal" data-variant-id="{{ $productVariant->id }}"
          data-variant-name="{{ $productVariant->name }}" data-review-id="{{ $productVariant->variantReview->id ?? '' }}"
          data-review-productreview="{{ $productVariant->variantReview->productreview ?? '' }}"
          data-image="{{ $defaultImage ? asset('public/storage/uploads/media/products/images/' . $defaultImage) : asset('public/backend/assetss/images/products/product_thumb.jpg') }}"><span
            class="material-symbols-outlined font20">edit</span> <u> {{ $isEdit ? 'Edit Review' : 'Rate & Write Review' }}
          </u></a>
      @endif
    @endauth --}}

    @if (auth()->check())
      @if ($isInWishlist)
        <a href="javascript:void();" data-id="{{ Hashids::encode($productVariant->id) }}"
          data-serial="{{ $productVariant->id }}" title="Wishlist" id="wishlist-icon" class="add-to-wishlist-btn">
          <span class="material-symbols-outlined c--red fillup-heart"
            id="wishlist-icon-fill-{{ $productVariant->id }}">favorite</span>
        </a>
      @else
        <a href="javascript:void();" data-id="{{ Hashids::encode($productVariant->id) }}"
          data-serial="{{ $productVariant->id }}" title="Wishlist" class="add-to-wishlist-btn">
          <span class="material-symbols-outlined" id="wishlist-icon-{{ $productVariant->id }}">favorite</span>
        </a>
      @endif
      <form id="add-to-wishlist-form-{{ $productVariant->id }}" action="{{ route('cart.add') }}" method="POST"
        style="display: none;">
        @csrf
        <input type="hidden" name="product_variant_id" value="{{ Hashids::encode($productVariant->id) }}">
        <input type="hidden" name="quantity" value="1">
        <input type="hidden" name="is_saved_for_later" value="1">
        <input type="hidden" name="action" value="add_to_wishlist">
      </form>
    @endif
  </div>

  @php
    // Keep only unique attributes by name (or id if you prefer)
    $uniqueAttributes = collect($attributeOptions)->unique('name')->values();
  @endphp

  <div class="product-options flow-rootX">
    @foreach ($uniqueAttributes as $attribute)
      @php
        $distinctOptions = collect($attribute['options'])->unique('attribute_value_id')->values();
      @endphp
      <div class="product-options flow-rootX {{ $attribute['name'] != 'Color' ? 'material-option' : '' }}"
        data-attribute-id="{{ $attribute['id'] }}">
        <h3 class="font22 fw-normal">Select {{ $attribute['name'] }} Option</h3>
        <div class="po-item-wrapper">
          @foreach ($distinctOptions as $option)
            @php
              $isSelected =
                  isset($currentAttributes[$attribute['id']]) &&
                  $currentAttributes[$attribute['id']] == $option['attribute_value_id'];
              $newAttributes = $currentAttributes->toArray();
              $newAttributes[$attribute['id']] = $option['attribute_value_id'];
              $matchedSku = null;
              foreach ($combinations as $combo) {
                  if ($combo['attributes'] == $newAttributes) {
                      $matchedSku = $combo['sku'];
                      break;
                  }
              }
              $imageUrl = asset('public/storage/uploads/media/products/images/' . $option['image_url']);
            @endphp
            <div class="po-items {{ $isSelected ? 'checked' : '' }}">
              <input type="radio" name="po-inp-{{ $attribute['id'] }}" value="{{ $option['attribute_value_id'] }}"
                data-sku="{{ $matchedSku ?? '' }}" {{ $isSelected ? 'checked' : '' }}>
              <div class="po-img ratio ratio-1000x800">
                @if ($attribute['name'] == 'Color')
                  <img src="{{ $imageUrl }}" alt="{{ $option['value'] }}" loading="lazy">
                @endif
              </div>
              <div class="po-info">
                {{ $option['value'] }}
                <span class="select-chk font14">
                  @if ($isSelected)
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                      class="bi bi-check" viewBox="0 0 16 16">
                      <path
                        d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                    </svg>
                  @endif
                </span>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endforeach
  </div>



  <div class="product-details-price py-3 d-flex align-items-center gap-3">
    @if ($productVariant->inventory?->quantity > 0)
      @php
        $promo = findSalePrice($productVariant->id);
        //pd($promo);
        //$hasPromo = is_array($promo) && isset($promo['promotion_price']);
        $displayPrice = $promo['display_price'];
        $regularPrice = $promo['regular_price'];
        $specialPrice = $promo['special_price'];

        // $promotionPrice = $promo['promotion_price'] ?? null;

      @endphp



      @if ($promo['regular_price_true'] == true)
        <p class="m-0 font20 price-wrapper d-flex gap-3">

          <span class="c--primary">{{ displayPrice($regularPrice) }}</span>

        </p>
      @else
        <p class="m-0 font20 price-wrapper d-flex gap-3">
          <span class="c--primary">{{ displayPrice($displayPrice) }}</span>
          <span class="c--oldprice text-decoration-line-through">{{ displayPrice($regularPrice) }}</span>
        </p>
        <p class="m-0 font16">Discount ({{ $promo['display_discount'] }} OFF)</p>
      @endif
      @if ($specialPrice)
        <span class="special-offer-badge-small">Special Offer</span>
      @endif
    @endif
  </div>


  @if ($productVariant->inventory?->quantity > 0)
    <div class="stock-delivery font18 d-flex align-items-center gap-4">
      <span>In stock</span>
      <div class="estimate_days"></div>
    </div>
    <div class="stock-delivery font18 d-flex align-items-center gap-3">
      <input type="text" class="form-control only-alphabet-numbers-symbols" name="pincode" id="pincode"
        placeholder="Enter Pincode" maxlength="15" autocomplete="off"
        value="{{ session('user_pincode')['Pincode'] ?? config('defaults.default_pincode') }}">
      <button type="submit" class="btn btn-dark pincode-apply-btn py-2">Apply</button>
    </div>
    <span class="pincode-message"></span>

    <div class="d-flex justify-content-end align-items-center gap-3" id="cart-btn-wrapper">
      {{-- <!-- Add to Cart Form --> --}}
      <form id="add-to-cart-form-{{ $productVariant->id }}" action="{{ route('cart.add') }}" method="POST"
        style="display: none;">
        @csrf
        <input type="hidden" name="product_variant_id" value="{{ Hashids::encode($productVariant->id) }}">
        <input type="hidden" name="quantity" value="1">
        <input type="hidden" name="is_saved_for_later" value="0">
        <input type="hidden" name="action" value="add_to_cart">
      </form>
      {{-- <a href="{{ route('cart.index') }}" class="btn btn-outline-dark w-50 py-3 view-cart-btn" id="view-cart-{{ $productVariant->id }}"
        style="display: none;">View
        Cart</a> --}}
      @if ($isInCart)
        <a href="{{ route('cart.index') }}" class="btn btn-outline-dark w-50 py-3 view-cart-btn"
          id="view-cart-{{ $productVariant->id }}">View Cart</a>
      @else
        <button type="button" data-id="{{ Hashids::encode($productVariant->id) }}"
          data-serial="{{ $productVariant->id }}"
          class="btn btn-outline-dark w-50 py-3 add-to-cart-btn browse-cart-loader"
          id="added-cart-{{ $productVariant->id }}">Add to
          Cart</button>
      @endif
      {{-- <!-- Buy Now Form --> --}}
      <form id="buy-now-form-{{ $productVariant->id }}" action="{{ route('cart.add') }}" method="POST"
        style="display: none;">
        @csrf
        <input type="hidden" name="product_variant_id" value="{{ Hashids::encode($productVariant->id) }}">
        <input type="hidden" name="quantity" value="1">
        <input type="hidden" name="is_saved_for_later" value="0">
        <input type="hidden" name="action" value="buy_now">
      </form>
      <button type="button" data-id="{{ Hashids::encode($productVariant->id) }}"
        data-serial="{{ $productVariant->id }}" class="btn btn-dark w-50 py-3 buy-now-btn">Buy
        Now</button>
    </div>
  @else
    <div class="stock-delivery font18 d-flex align-items-center gap-4">
      <span class="text-danger">Out of stock</span>
    </div>
  @endif
  @include('frontend.includes.accordion')
  @include('frontend.includes.ratings')

</div>

@push('scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script>
    $(document).ready(function() {
      // Hide elements initially
      $('.pincode-message').hide();
      $('.estimate_days').hide();

      function checkPincode(pincode) {
        if (pincode.length === 0) {
          $('.pincode-message')
            .show()
            .html('Please enter pincode')
            .css('color', 'red');
          $('.estimate_days').hide();
          return;
        }

        $.ajax({
          url: "{{ route('pincode.check') }}",
          type: "POST",
          data: {
            pincode: pincode,
            _token: "{{ csrf_token() }}"
          },
          dataType: "json",
          success: function(response) {
            if (response.success && response.is_serviceable) {
              if (response.data.estimate_days != null) {
                $('.estimate_days').html(`Delivery ${response.data.estimate_days}`);
              }
              $('.pincode-message')
                .show()
                .html('Pincode is serviceable')
                .css('color', 'green');

              $('.estimate_days').show();
              $('#cart-btn-wrapper').removeClass('d-none');

            } else {
              $('.pincode-message')
                .show()
                .html('Pincode is not serviceable!!')
                .css('color', 'red');
              $('.estimate_days').hide();
              $('#cart-btn-wrapper').addClass('d-none');
            }
          },
          error: function() {
            $('.pincode-message')
              .show()
              .html('Invalid pincode format')
              .css('color', 'orange');
          }
        });
      }

      // On button click
      $('.pincode-apply-btn').on('click', function(e) {
        e.preventDefault();
        const pincode = $('#pincode').val();
        checkPincode(pincode);
      });

      // On page load, if input has value
      const initialPincode = $('#pincode').val();
      if (initialPincode.length >= 3) {
        checkPincode(initialPincode);
      }
    });
  </script>
  <script>
    document.addEventListener('click', function(e) {
      if (e.target.matches('input[type="radio"][name^="po-inp-"]')) {
        let sku = e.target.dataset.sku;
        if (sku && sku.trim() !== '') {
          window.location.href = `{{ route('product.show', ['variant' => ':sku']) }}`.replace(':sku', sku);
        } else {
          iziNotify("", 'No SKU for this selection, not redirecting.', "error");
        }
      }
    });
  </script>
@endpush
