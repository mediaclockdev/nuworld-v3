@props(['order'])
<div class="furniture__cartsummery-inside">
  <div class="individual_blocks flow-rootX border-bottom border-secondery">
    <h3 class="font25 c--blackc fw-normal"><strong>Shipping Address</strong></h3>
    @php
      $shippingAddress = json_decode($order->shipping_address, true) ?? '';
    @endphp

    @if (is_array($shippingAddress))
      <div class="shipping-address">
        @isset($shippingAddress['name'])
          <h4 class="fw-medium font20">{{ $shippingAddress['name'] }}</h4>
          <p class="mb-0 font16">{{ $shippingAddress['address'] }},
            <br>
            Phone: {{ $shippingAddress['phone'] ?? '' }}
          </p>
        @endisset
      </div>
    @else
      <p class="mb-0 text-muted">No shipping address provided</p>
    @endif
  </div>
  <div class="individual_blocks flow-rootX border-bottom border-secondery">
    <h3 class="font25 c--blackc fw-normal"><strong>Billing Address</strong></h3>@php
      $billingAddress = json_decode($order->billing_address, true) ?? '';
    @endphp

    @if (is_array($billingAddress))
      <div class="billing-address">
        @isset($billingAddress['name'])
          <h4 class="fw-medium font20">{{ $billingAddress['name'] }}</h4>
          <p class="mb-0 font16">{{ $billingAddress['address'] }},
            <br>
            Phone: {{ $billingAddress['phone'] ?? '' }}
          </p>
        @endisset
      </div>
    @else
      <p class="mb-0 text-muted">No billing address provided</p>
    @endif
  </div>
  <div class="individual_blocks flow-rootX">
    <h3 class="font25 c--blackc fw-normal"><strong>Payment Info</strong></h3>
    <p class="mb-0">{{ $order->payment_method_display }}</p>
  </div>
</div>
