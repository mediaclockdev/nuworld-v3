<!DOCTYPE html>
<html lang="en">

<head>
  @include('frontend.layouts.includes.head')

  @stack('component-styles')
  @stack('styles')

  <title>@yield('title', 'E-Commerce') | Mayuri</title>

  <style>
    .error {
      color: red;
    }

    .dot-loader-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 5px;
      height: 1em;
    }

    .dot-loader-btn span {
      width: 6px;
      height: 6px;
      background-color: #000;
      /* match .btn-outline-dark */
      border-radius: 50%;
      animation: dotBounce 1s infinite ease-in-out;
    }

    .dot-loader-btn span:nth-child(2) {
      animation-delay: 0.2s;
    }

    .dot-loader-btn span:nth-child(3) {
      animation-delay: 0.4s;
    }

    @keyframes dotBounce {

      0%,
      80%,
      100% {
        transform: scale(0.6);
        opacity: 0.5;
      }

      40% {
        transform: scale(1);
        opacity: 1;
      }
    }

    .special-offer-badge-small {
      display: inline-block;
      padding: 0.2rem 0.5rem;
      font-size: 0.7rem;
      font-weight: bold;
      color: #fff;
      background: linear-gradient(270deg, #ff6a00, #ff3d00, #ff6a00);
      background-size: 400% 400%;
      border-radius: 0.3rem;
      box-shadow: 0 0 6px rgba(255, 100, 0, 0.7);
      animation: smallGradient 3s ease infinite, smallPulse 1.5s infinite;

    }

    @keyframes smallGradient {
      0% {
        background-position: 0% 50%;
      }

      50% {
        background-position: 100% 50%;
      }

      100% {
        background-position: 0% 50%;
      }
    }

    @keyframes smallPulse {

      0%,
      100% {
        transform: scale(1);
      }

      50% {
        transform: scale(1.05);
      }
    }

    /* .navbar .dropdown-menu {
      display: none;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.2s ease, visibility 0.2s ease;
    }

    .navbar .dropdown-menu.show {
      display: block;
      opacity: 1;
      visibility: visible;
    } */
  </style>
</head>

<body class="theme_mayuri">

  <!-- Global Loader -->
  {{-- <div id="global-loader" class="d-none">
    <div class="loader-container">
      <div class="dotted-spinner">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
      <span class="loader-text">Loading......</span>
    </div>
  </div> --}}

  {{-- Header / Navbar --}}
  @include('frontend.layouts.includes.header')

  {{-- Main Content Area --}}
  <main>
    @yield('content')

  </main>
  @if ($showDisclaimer ?? true)
    @include('frontend.includes.disclaimer')
  @endif
  {{-- Footer --}}
  @include('frontend.layouts.includes.footer')
  <!-- Address Location Modal -->
  <div class="modal genericmodal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title font24 fw-normal ">Choose your location</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body flow-rootX2">
          <div class="border-top"></div>
          <!-- Instruction -->
          @if (!auth()->check())
            <p class="text-muted mb-4 small">
              Select a delivery location to see product availability and delivery options
            </p>
            <div class="text-center mb-3">
              <a href="{{ route('signuplogin') }}" class="btn btn-warning fw-bold w-100" style="color: #000;">
                Sign in to see your addresses
              </a>
            </div>
            <!-- Divider -->
            {{-- <div class="text-center text-muted my-2 small">Or enter an Indian pincode</div> --}}
          @endif

          <!-- Form -->
          {{-- <form id="locationForm">
            @csrf
            <div class="input-group">
              <input type="text" name="pincode" id="pincode" class="form-control" placeholder="Enter Pincode"
                maxlength="6" required value="{{ session('user_pincode')['Pincode'] ?? '' }}">
              <button type="submit" class="btn btn-outline-secondary">Apply</button>
            </div>
            <span class="d-none text-danger mt-2 d-none small" id="location-error">Invalid pincode</span>
          </form> --}}
        </div>
      </div>
    </div>
  </div>

  <!-- Address List Modal -->
  @if ($userAddresses = session('user_addresses'))
    <div class="modal genericmodal fade" id="listOfAddressModal" tabindex="-1"
      aria-labelledby="listOfAddressModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title font24 fw-normal">Address</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body flow-rootX2">
            <div class="border-top"></div>
            <a href="javascript:void(0);" class="btn btn-outline-dark d-inline-flex px-4 py-3 align-items-center gap-2"
              title="Add New Address" id="openCreateAddressModalBtn">
              <span class="material-symbols-outlined">add</span> Add New Address
            </a>
            <form id="select-address-form" method="POST" action="{{ route('address.set-default') }}">
              @csrf
              <div class="profile_details overflow-hidden flow-rootX3 h-100">
                <div class="info flow-rootX2">
                  @include('frontend.includes.list-of-address', ['userAddresses' => $userAddresses])
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endif

  <!-- Create Address Modal -->
  <div id="createNewAddressModalContainer"></div>

  {{-- Scripts --}}
  @include('frontend.layouts.includes.scripts')

  @stack('component-scripts')
  @stack('scripts')
  {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}

  <script>
    $(document).on('click', '.add-to-cart-btn', function(e) {
      e.preventDefault();

      const btn = $(this);
      const id = btn.data('id');
      const serial = btn.data('serial');
      const form = $('#add-to-cart-form-' + serial);
      if (!form.length) {
        console.error('Add to cart form not found for serial:', serial);
        return;
      }

      const formData = new FormData(form[0]);

      // --- BEFORE SEND ---
      btn.prop('disabled', true);
      const originalText = btn.html();

      // Replace button content with loader
      btn.html(`
    <div class="dot-loader-btn">
      <span></span><span></span><span></span>
    </div>
  `);

      // Optional: global overlay
      $('#global-loader').removeClass('d-none');

      // --- FETCH REQUEST ---
      fetch(form.attr('action'), {
          method: 'POST',
          body: formData,
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
          },
        })
        .then(response => response.json())
        .then(data => {
          $('#global-loader').addClass('d-none');
          btn.prop('disabled', false);

          if (data.success) {
            // --- SUCCESS ---
            const heartIcon = $('#wishlist-icon-fill-' + serial);
            if (data.isInCart) {
              heartIcon.removeClass('c--red fillup-heart');
              heartIcon.attr('data-filled', 'false');
            }

            $('.cart-number').toggleClass('d-none', !(data.cart_count > 0))
              .text(data.cart_count || '');
            $('.wishlist-number').toggleClass('d-none', !(data.wishlist_count > 0))
              .text(data.wishlist_count || '');

            if (data.cart_html) $('#cart-items-wrapper').html(data.cart_html);
            if (data.wishlist_html) $('#wishlist-items-wrapper').html(data.wishlist_html);
            if (data.cart_summary_html) $('#cart-summary-wrapper').html(data.cart_summary_html);

            if (data.cart_count > 0) {
              $('.furniture__cartsummery-right').removeClass('d-none').show();
              $('#proceed-to-checkout')
                .removeClass('disabled')
                .attr('href', '{{ route('checkout') }}')
                .css('pointer-events', 'auto');
            }

            if (data.wishlist_count === 0)
              $('.all-product-card-item').html('<h2>No item exists</h2>');

            // --- SWITCH BUTTON TO VIEW CART ---
            btn.hide();
            const addedToCartBtn = $('#added-cart-' + serial);
            const viewCartLink = $('<a>', {
              href: "{{ route('cart.index') }}",
              class: 'btn btn-outline-dark w-50 py-3 view-cart-btn',
              text: 'View Cart',
              id: 'view-cart-' + serial,
            });
            addedToCartBtn.after(viewCartLink);

            iziNotify("", data.message, "success");
          } else {
            // Restore button text if not success
            btn.html(originalText);
          }
        })
        .catch(error => {
          $('#global-loader').addClass('d-none');
          btn.prop('disabled', false);
          btn.html(originalText);
          iziNotify("Error!", error.responseJSON?.message || 'Something went wrong. Please try again.', "error");
        });
    });




    $(document).on('click', '.add-to-wishlist-btn', function(e) {
      e.preventDefault();
      const btn = $(this);
      const id = $(this).data('id');
      const serial = $(this).data('serial');
      const form = $('#add-to-wishlist-form-' + serial);
      const formData = new FormData(form[0]);
      fetch(form.attr('action'), {
          method: 'POST',
          body: formData,
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
          },
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Update counts

            // Update wishlist icon
            const heartIcon = btn.find('.material-symbols-outlined');
            if (data.isInWishlist) { // Add to wishlist
              heartIcon.addClass('c--red fillup-heart');
              heartIcon.attr('data-filled', 'true');
            } else {
              heartIcon.removeClass('c--red fillup-heart');
              heartIcon.attr('data-filled', 'false');
            }

            // Update cart button
            let viewCartBtn = $('#view-cart-' + serial);
            if (data.isInWishlist) {
              let addCartBtn = $('<button>', {
                type: 'button',
                'data-id': id,
                'data-serial': serial,
                class: 'btn btn-outline-dark w-50 py-3 add-to-cart-btn',
                id: 'added-cart-' + serial,
                text: 'Add to Cart'
              });
              viewCartBtn.hide();
              viewCartBtn.after(addCartBtn);
            }

            if (data.cart_count > 0) {
              $('.cart-number').removeClass('d-none').text(data.cart_count);
            } else {
              $('.cart-number').addClass('d-none').text('');
            }

            if (data.wishlist_count > 0) {
              $('.wishlist-number').removeClass('d-none').text(data.wishlist_count);
            } else {
              $('.wishlist-number').addClass('d-none').text('');
            }

            // Dynamically update cart and wishlist sections
            if (data.cart_html) {
              $('#cart-items-wrapper').html(data.cart_html);
            }
            if (data.wishlist_html) {
              $('#wishlist-items-wrapper').html(data.wishlist_html);
            }
            if (data.cart_count < 1) {
              $('.furniture__cartsummery-right').addClass('d-none');
            }
            if (data.cart_summary_html) {
              $('#cart-summary-wrapper').html(data.cart_summary_html);
            }

            iziNotify("", data.message, "success");
          }
        })
        .catch(error => iziNotify("Error!", error.responseJSON?.message || 'Something went wrong. Please try again.',
          "error"));
    });
  </script>

  <script>
    $('.locationModal').on('click', function(e) {
      e.preventDefault();
      // $('#locationForm')[0].reset();
      $('#locationModal').modal('show');
    });

    $.validator.addMethod("lettersNumbersHyphensSpaces", function(value, element) {
      return this.optional(element) || /^[a-zA-Z0-9\- ]+$/.test(value);
    }, "Only letters, numbers, hyphens and spaces are allowed.");

    /* $('#locationForm').validate({
      rules: {
        pincode: {
          required: true,
          minlength: 3,
          maxlength: 15,
          lettersNumbersHyphensSpaces: true
        },
      },
      messages: {
        pincode: {
          required: "Pincode field is required",
          lettersNumbersHyphensSpaces: "Only letters, numbers, hyphens and spaces are allowed"
        },
      },
      submitHandler: function(form) {
        const formData = new FormData(form);
        const errorEl = $('#location-error');
        const submitBtn = $(form).find('button[type="submit"]');

        errorEl.addClass('d-none').text('');
        submitBtn.prop('disabled', true).text('Applying...');
        $.ajax({
          url: `{{ route('location.set') }}`,
          type: 'POST',
          headers: {
            'X-CSRF-TOKEN': formData.get('_token'),
            'Accept': 'application/json'
          },
          data: formData,
          contentType: false,
          processData: false,
          success: function(data) {
            submitBtn.prop('disabled', false).text('Apply');
            if (data.success) {
              $('#locationModal').modal('hide');
              location.reload();
            } else {
              errorEl.removeClass('d-none').text(data.message);
            }
          },
          error: function(xhr) {
            submitBtn.prop('disabled', false).text('Apply');
            errorEl.removeClass('d-none').text('Server error or invalid response');
          }
        });
      }
    }); */


    // Function to refresh the address list
    window.refreshAddressList = function() {
      $.ajax({
        url: '{{ route('address.list') }}',
        type: 'GET',
        success: function(response) {
          if (response.success) {
            $('.address_block').replaceWith(response.html);
            $('#address_block_profile').replaceWith(response.html2);
          } else {
            iziNotify("", response.message, "error");
          }
        },
        error: function() {
          iziNotify("", 'Failed to load address list.', "error");
        }
      });
    };

    // Handle submit-default-address-btn click
    $(document).on('click', '.submit-default-address-btn', function() {
      var addressId = $('input[name="user_default_address"]:checked').val();
      if (!addressId) {
        iziNotify("", 'Please select an address.', "error");
        return;
      }
      $.ajax({
        url: '{{ route('address.set-default') }}',
        method: 'POST',
        data: {
          id: addressId,
          is_default: 1,
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          if (response.success) {
            // Refresh the address list
            window.refreshAddressList();
            // Update header pincode
            $('.default-pin').text(
              `${response.user_pincode.Name || '{{ config('defaults.default_location') }}'} ${response.user_pincode.Pincode || '{{ config('defaults.default_pincode') }}'}`
            );
            iziNotify("", response.message, "success");
            $('#listOfAddressModal').modal('hide');
          } else {
            iziNotify("", response.message, "error");
          }
        },
        error: function(xhr) {
          iziNotify("", 'An error occurred while updating the address.', "error");
        }
      });
    });
  </script>

</body>

</html>
