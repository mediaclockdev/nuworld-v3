@extends('frontend.layouts.app')

@section('title', @$title)
{{-- @php
  pd($order->orderHistories);
@endphp --}}
@section('content')
  <section class="breadcrumb-wrapper py-4 border-top">
    <div class="container-xxl">
      <ul class="breadcrumbs">
        <li><a href="javascript:void">Home</a></li>
        {{-- <li><a href="javascript:void">Furniture</a></li>
        <li><a href="javascript:void">Sofas</a></li>
        <li><a href="javascript:void">Sofa Sets</a></li>
        <li><a href="javascript:void">Mid-Century Modern Sofa Sets</a></li>
        <li><a href="javascript:void">Cart</a></li>
        <li><a href="javascript:void">Checkout</a></li> --}}
        <li>Order Confirmation</li>
      </ul>
    </div>
  </section>
  <section class="furniture_track_order_wrap pt-4">
    <div class="container-xxl flow-rootX3">
      <h2 class="fw-normal mt-0 font45 c--blackc">Track Package</h2>
      <div class="furniture_cart_inside_wrap">
        <div class="furniture_cart_left">
          <div class="inside">
            <div class="cart_block flow-rootX2">
              <div class="cart_grid flow-rootX">

                <!-- Each Cart Card -->
                @foreach ($items as $item)
                  @php
                    $regularPrice = $item->regular_price;
                    $sellPrice = $item->sell_price;
                    $showDiscount = $regularPrice > $sellPrice && $regularPrice > 0;
                    $discountPercent = $showDiscount ? round((($regularPrice - $sellPrice) / $regularPrice) * 100) : 0;
                  @endphp
                  <div class="cart_page_block for_track border">
                    <div class="product_thumb">
                      <figure class="m-0 ratio ratio-1000x800"><a href="#" title="The Floral Collection"><img
                            src="{{ asset('public/frontend/assets/img/product/product_thumb.jpg') }}" alt="Mayuri"
                            title="Mayuri" class="imageFit"></a></figure>
                    </div>
                    <div class="product_info border-start d-flex justify-content-center flex-column p-4 flow-rootX2">
                      <div class="product_name_category flow-rootx">
                        <h5 class="font30 fw-normal c--blackc mt-0">{{ $item->variant ? $item->variant->name : '' }}</h5>
                        {{-- <h4 class="font18 fw-normal c--blackc m-0">Orange Color Selected / (12 Months' Onsite Warranty) --}}
                        </h4>
                      </div>
                      <div class="product-details-price d-flex align-items-center gap-3">
                        <p class="m-0 font30 price-wrapper d-flex gap-3">
                          <span class="c--primary">
                            {{ displayPrice($sellPrice) }}
                          </span>

                          @if ($showDiscount)
                            <span class="c--oldprice text-decoration-line-through">
                              {{ displayPrice($regularPrice) }}
                            </span>
                          @endif
                        </p>

                        @if ($showDiscount)
                          <p class="m-0 font18 c--success">
                            ({{ $discountPercent }}% Discount)
                          </p>
                        @endif
                      </div>

                    </div>
                  </div>
                @endforeach
                {{-- <div class="cart_page_block for_track border">
                  <div class="product_thumb">
                    <figure class="m-0 ratio ratio-1000x800"><a href="#" title="The Floral Collection"><img
                          src="{{ asset('public/frontend/assets/img/product/product_thumb.jpg') }}" alt="Mayuri" title="Mayuri"
                          class="imageFit"></a></figure>
                  </div>
                  <div class="product_info border-start d-flex justify-content-center flex-column p-4 flow-rootX2">
                    <div class="product_name_category flow-rootx">
                      <h5 class="font30 fw-normal c--blackc mt-0">The Floral Collection</h5>
                      <h4 class="font18 fw-normal c--blackc m-0">Orange Color Selected / (12 Months' Onsite Warranty)</h4>
                    </div>
                    <div class="product-details-price d-flex align-items-center gap-3">
                      <p class="m-0 font25 price-wrapper d-flex gap-3"><span
                          class="c--primary">{{ displayPrice(1200.0) }}</span><span
                          class="c--oldprice text-decoration-line-through">{{ displayPrice(1599.0) }}</span></p>
                    </div>
                  </div>
                </div> --}}
              </div>
            </div>
            @php
              $statuses = getStatusesLog();
              $statusIcons = [
                  1 => 'check', // Confirmed
                  2 => 'sync_problem', // Cancellation Initiated
                  3 => 'cancel', // Cancelled
                  4 => 'local_shipping', // Shipped
                  5 => 'house', // Delivered
              ];
              $historyMap = $order->orderHistories->keyBy('status');

              // Remove cancellation statuses (2, 3) if they don't exist in order history
              foreach ([2, 3] as $cancelStatus) {
                  if (!isset($historyMap[$cancelStatus])) {
                      unset($statuses[$cancelStatus]);
                  }
              }
            @endphp
            <div class="furniture__track_order_list border border-top-0 p-4 flow-rootX2">
              <h2 class="fw-normal mt-0 font25 c--blackc">Arriving
                {{ \Carbon\Carbon::parse($order->orderHistories->last()['scheduled_date'])->format('jS M') }}</h2>
              <div class="track_card_wrap">
                @foreach ($statuses as $statusId => $statusLabel)
                  @php
                    $isActive = $historyMap->has($statusId);
                    $statusIcon = $statusIcons[$statusId] ?? 'info';
                    $datetime = $isActive
                        ? \Carbon\Carbon::parse(
                            $historyMap[$statusId]['scheduled_date'] . ' ' . $historyMap[$statusId]['scheduled_time'],
                        )->format('M j, Y \a\t g:iA')
                        : null;
                  @endphp

                  <div class="track_card {{ $isActive ? 'active' : '' }}">
                    <div class="blk">
                      <div class="icon">
                        <span class="material-symbols-outlined c--whitec">{{ $statusIcon }}</span>
                      </div>
                      <div class="txt">
                        <h4 class="fw-normal">{{ $statusLabel }}</h4>
                        @if ($datetime)
                          <p class="font14 c--gry">{{ $datetime }}</p>
                        @endif
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
            <div
              class="furniture__track_action border border-top-0 p-4 d-flex justify-content-end align-items-center gap-2">
            </div>
          </div>
        </div>
        <div class="furniture__cartsummery-right">
          <x-cart-summary-after-order :order="$order" />
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
@endpush
