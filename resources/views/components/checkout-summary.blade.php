@props(['cart_items', 'shipping_address', 'billing_address'])
<div class="furniture__cartsummery-inside">
  <input type="hidden" name="coupon_id" id="coupon_id" value="">
  <input type="hidden" name="coupon_discount" id="coupon_discount" value="">
  <input type="hidden" name="shipping_address" id="shipping_address" value="{{ $shipping_address->id ?? '' }}">
  <input type="hidden" name="billing_address" id="billing_address" value="{{ $billing_address->id ?? '' }}">
  <x-address-display title="Shipping Address" :userAddress="$shipping_address" addressType="shipping" />
  <x-address-display title="Billing Address" :userAddress="$billing_address" addressType="billing" />
  <x-cart-summary :cart_items="$cart_items" :coupon="true" />
  @if (cartCount() > 0)
    <x-payment-methods />
    <div class="individual_blocks pt-0">
      <div class="cart_action">
        <button id="payNowBtn" class="btn btn-dark w-100 py-3">Pay Now</button>
      </div>
    </div>
  @endif
</div>
