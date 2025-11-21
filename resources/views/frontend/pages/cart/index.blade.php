@extends('frontend.layouts.app')
@push('styles')
  <style>
    .custom-tooltip {
      position: relative;
      cursor: pointer;
    }

    .custom-tooltip::after {
      content: attr(data-tooltip);
      position: absolute;
      bottom: 125%;
      left: 50%;
      transform: translateX(-50%);
      background-color: #333;
      color: #fff;
      padding: 8px 12px;
      border-radius: 6px;
      white-space: nowrap;
      font-size: 14px;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.2s ease-in-out;
      z-index: 1000;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    /* Tooltip arrow */
    .custom-tooltip::before {
      content: "";
      position: absolute;
      bottom: 115%;
      left: 50%;
      transform: translateX(-50%);
      border-width: 6px;
      border-style: solid;
      border-color: #333 transparent transparent transparent;
      opacity: 0;
      transition: opacity 0.2s ease-in-out;
      z-index: 1001;
    }

    /* Show tooltip on hover */
    .custom-tooltip:hover::after,
    .custom-tooltip:hover::before {
      opacity: 1;
    }
  </style>
@endpush

@section('title', @$title)

@section('content')
  <section class="breadcrumb-wrapper py-4 border-top">
    <div class="container-xxl">
      <ul class="breadcrumbs">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li>Cart</li>
      </ul>
    </div>
  </section>

  <section class="furniture_cart_wrap pt-4">
    <div class="container-xxl flow-rootX3">
      <h2 class="fw-normal m-0 font45 c--blackc">Cart</h2>
      <div class="furniture_cart_inside_wrap">
        <x-cart-items :c_items="$cart_items" :s_items="$saved_for_later_items" :display_quantity="true"/>

        <div class="furniture__cartsummery-right {{ cartCount() == 0 ? 'd-none' : '' }}">
          <div class="furniture__cartsummery-inside">
            <div id="cart-summary-wrapper">
              <x-cart-summary :cart_items="$cart_items" />
            </div>
            <div class="individual_blocks pt-0">
              <div class="cart_action">
                <a id="proceed-to-checkout" href="{{ cartCount() > 0 ? route('checkout') : 'javascript:void(0)' }}"
                  class="btn btn-dark w-100 py-3 {{ cartCount() > 0 ? '' : 'disabled' }}">Proceed To Checkout</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  @include('frontend.includes.checkout-more-products')
@endsection

@push('scripts')
@endpush
