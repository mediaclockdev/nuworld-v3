@extends('frontend.layouts.app')
@push('styles')
  <style>
    .tooltip-inner {
      background-color: #333;
      color: #fff;
      font-size: 14px;
      padding: 8px 12px;
      border-radius: 6px;
    }

    .tooltip.bs-tooltip-top .tooltip-arrow::before {
      border-top-color: #333;
    }
  </style>
@endpush
@section('title', @$title)

@section('content')
  <section class="breadcrumb-wrapper py-4 border-top">
    <div class="container-xxl">
      <ul class="breadcrumbs">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('cart.index') }}">Cart</a></li>
        <li>Checkout</li>
      </ul>
    </div>
  </section>
  <section class="furniture_checkout_wrap pt-4">
    <div class="container-xxl flow-rootX3">
      <h2 class="fw-normal m-0 font45 c--blackc">Checkout</h2>
      <div class="furniture_cart_inside_wrap">
        <x-cart-items :c_items="$cart_items" :display_quantity="false" />
        <div class="furniture__cartsummery-right">
          <input type="hidden" id="address_count" value="{{ !empty($addresses) ? count($addresses) : 0 }}">
          <x-checkout-summary :cart_items="$cart_items" :shipping_address="$shipping_address" :billing_address="$billing_address" />
        </div>
      </div>
    </div>
  </section>

  <x-address-modal :addresses="$addresses ?? []" />
  <x-checkout-create-address-modal :states="$states" />
@endsection

@push('component-scripts')
  <script>
    $('#payNowBtn').click(function(e) {
      e.preventDefault();
      if (!$('#billing_address').val() || !$('#shipping_address').val()) {
        iziNotify("", 'Please add both billing and shipping address', "error");
        return;
      }
      $(this).prop('disabled', true).html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
      );

      $.ajax({
        url: '{{ route('checkout.process') }}',
        method: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          billing_address: $('#billing_address').val(),
          shipping_address: $('#shipping_address').val(),
          coupon_id: $('#coupon_id').val(),
          coupon_discount: $('#coupon_discount').val(),
          payment_method: $('input[name="paymentmode"]:checked').val(),
        },
        success: function({
          success,
          message,
          redirect
        }) {
          if (success) {
            // iziNotify("", message, "success");
            setTimeout(() => window.location.href = `${redirect}`, 1000);
          } else {
            iziNotify("Oops!", message, "error");
            $('#payNowBtn').prop('disabled', false).html('Pay Now');
          }
        },
        error: function(xhr) {
          let errorMessage = xhr.responseJSON?.message || 'An error occurred during payment processing';
          iziNotify("Oops!", errorMessage, "error");
          $('#payNowBtn').prop('disabled', false).html('Pay Now');
        }
      });
    });
  </script>
@endpush
