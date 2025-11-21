@props(['c_items' => [], 's_items' => [], 'cart_action' => true, 'display_quantity'])

<div class="furniture_cart_left">
  <div class="inside flow-rootX3">
    <div class="cart_block flow-rootX2">
      <div class="cart_grid flow-rootX">
        {{-- Cart Items Section --}}
        <div id="cart-items-wrapper" class="flow-rootX2">
          @include('frontend.includes.cart_items', ['items' => $c_items,'display_quantity' => $display_quantity])
        </div>

        {{-- Wishlist / Saved for Later Section --}}
         {{-- @if (request()->segment(1) !== 'checkout') --}}
         @if (request()->segment(1) !== 'checkout')
            <h2 class="mt-5 mb-3">Wishlist</h2>
            <div id="wishlist-items-wrapper">
              @include('frontend.includes.wishlist_items', ['items' => $s_items])
            </div>
          @endif
      </div>
    </div>
  </div>
</div>
