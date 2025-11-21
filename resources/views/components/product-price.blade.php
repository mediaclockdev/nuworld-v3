@props(['variant'])

@php
  $promo = findSalePrice($variant->id);
  $displayPrice = $promo['display_price'];
  $regularPrice = $promo['regular_price'];
  $discount = $promo['display_discount'];
@endphp

<div class="product-details-price d-flex align-items-center gap-3">
  @if ($promo['regular_price_true'] == true)
    <p class="m-0 font30 price-wrapper d-flex gap-3">
      <span class="c--primary">{{ displayPrice($regularPrice) }}</span>
    </p>
  @else
    <p class="m-0 font30 price-wrapper d-flex gap-3">
      <span class="c--primary">{{ displayPrice($displayPrice) }}</span>
      <span class="c--oldprice text-decoration-line-through">{{ displayPrice($regularPrice) }}</span>
    </p>
    @if ($discount > 0)
      <p class="m-0 font18 c--success">({{ $discount }} Discount)</p>
    @else
      <p class="m-0 font18 c--success">(No Discount)</p>
    @endif
  @endif
</div>
