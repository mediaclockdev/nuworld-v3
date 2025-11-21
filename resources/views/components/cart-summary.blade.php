@props(['cart_items', 'coupon' => false])

<div class="individual_blocks cart_summery flow-rootX border-secondery {{ cartCount() == 0 ? 'd-none' : '' }}">

  <h2 class="fw-normal m-0 font25 c--blackc">Cart Summary</h2>

  <div
    class="added_cart d-flex justify-content-between align-items-center gap-3 border-top border-bottom border-secondary py-3">
    <p class="font18 m-0">
      Your Cart
      <span class="c--gry">({{ cartCount() }} {{ cartCount() == 1 ? 'Item' : 'Items' }})</span>
    </p>
    <p class="font18 m-0">Shipping & taxes calculated at checkout</p>
  </div>
  @php
    use Illuminate\Support\Facades\DB;

    $extra_charges = DB::table('charges')->where('status', true)->get();
    //pd($extra_charges);
  @endphp
  <div class="cart_totals_grid">
    @php
      $total = 0;
      $totalTax = 0;
      $totalCategoryTaxPercent = 0;
    @endphp

    @foreach ($cart_items as $item)
      @php
        $variant = $item->productVariant;
        $promo = findSalePrice($variant->id);

        $displayPrice = $promo['display_price'];
        $regularPrice = $promo['regular_price'];
        $discount = $promo['display_discount'];
        //pd($discount);
        if ($promo['regular_price_true'] == true) {
            $unitPrice = $regularPrice;
            //$discountPercent = round((($regularPrice - $promotionPrice) / $regularPrice) * 100);
        } else {
            $unitPrice = $displayPrice;
            //$discountPercent = 0;
        }

        $subtotal = $unitPrice * $item->quantity;
        $total += $subtotal;

        $categoryTaxRate = $variant?->category?->tax ?? 0;
        $itemTax = ($unitPrice * $item->quantity * $categoryTaxRate) / 100;
        $totalTax += $itemTax;
        $totalCategoryTaxPercent += $categoryTaxRate;
      @endphp

      <div class="added_cart d-flex justify-content-between align-items-center gap-3 py-2">
        <div>
          <p class="font18 m-0">
            <a style="all: unset; cursor: pointer;" href="{{ route('product.show', $variant->sku) }}">
              {{ $variant->name }}
            </a> × {{ $item->quantity }}
            <i class="ri-information-line text-primary ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top"
              title="{{ config('defaults.tax_name') }}: {{ $categoryTaxRate }}% ({{ displayPrice($itemTax) }})">
            </i>



          </p>
          @if ($discount > 0)
            <small class="text-success">Discount ({{ $discount }} OFF)</small>
          @endif
        </div>
        <p class="font18 m-0">{{ displayPrice($subtotal) }}</p>
      </div>
    @endforeach

    @php
      $avgTax = count($cart_items) > 0 ? $totalCategoryTaxPercent / count($cart_items) : 0;
      $grandTotal = $total + $totalTax;

      // $discount = session('coupon.discount', 0);
      // $couponCode = session('coupon.code', null);

      //$finalTotal = max($grandTotal - $discount, 0);

    @endphp

    {{-- <div class="added_cart d-flex justify-content-between align-items-center gap-3 py-2">
      <p class="font18 m-0">Delivery Charges</p>
      <p class="font18 m-0">{{ displayPrice(0.0) }}</p>
    </div> --}}

    <div class="added_cart d-flex justify-content-between align-items-center gap-3 py-2">
      <p class="font18 m-0">
        Tax
        {{-- <i class="ri-information-line text-primary ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top"
          title="Total {{ config('defaults.tax_name') }}: {{ $avgTax }}%"></i> --}}
      </p>
      <p class="font18 m-0">{{ displayPrice($totalTax) }}</p>
    </div>
    {{-- === Delivery Charges Section === --}}
    @php
      $deliveryChargesTotal = 0;
    @endphp

    @foreach ($extra_charges as $charge)
      @php
        $applyCharge = true;
        $conditions = json_decode($charge->conditions, true) ?? [];

        // Apply min_order condition
        if (isset($conditions['min_order']) && $grandTotal < $conditions['min_order']) {
            $applyCharge = false;
        }

        if (!$applyCharge) {
            continue;
        }

        $chargeAmount = 0;
        switch ($charge->calculation_method) {
            case 'fixed':
                $chargeAmount = $charge->value;
                break;
            case 'percentage':
                $chargeAmount = ($grandTotal * $charge->value) / 100;
                break;
        }

        // Skip if charge amount is 0
        if ($chargeAmount <= 0) {
            continue;
        }

        $deliveryChargesTotal += $chargeAmount;
      @endphp

      <div class="added_cart d-flex justify-content-between align-items-center gap-3 py-2">
        <p class="font18 m-0">
          {{ $charge->name }}
          @if ($charge->calculation_method === 'percentage')
            <small class="text-muted">({{ $charge->value }}%)</small>
          @endif
        </p>
        <p class="font18 m-0">{{ displayPrice($chargeAmount) }}</p>
      </div>
    @endforeach

    @php
      $grandTotalWithDelivery = $grandTotal + $deliveryChargesTotal;
    @endphp



    <div id="coupon-section"></div>
    @if ($coupon)


      <form id="coupon-form">
        @csrf
        <div class="stock-delivery font18 d-flex align-items-center gap-4">
          <input type="hidden" name="order_amount" id="order_amount" value="{{ $grandTotalWithDelivery }}">
          <input type="text" name="coupon_code" id="coupon_code" class="form-control"
            placeholder="Enter coupon code">
          <button type="submit" class="btn btn-dark py-2">Apply</button>
        </div>
      </form>
      <div class="search-coupon text-right mt-3">
        <a href="javascript: javascript: void(0);" onclick="showCouponModal()">
          Search Coupons
        </a>
      </div>
    @endif

    <div class="added_cart d-flex justify-content-between align-items-center gap-3 py-2">
      <p class="font25 fw-medium m-0">Order Total</p>
      <p class="font25 fw-medium m-0 order-total-amount">{{ displayPrice($grandTotalWithDelivery) }}</p>
    </div>
  </div>


</div>

{{-- <!-- Coupon Modal --> --}}
<div class="modal genericmodal fade" id="couponModal" tabindex="-1" aria-labelledby="couponModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="couponModalLabel">Available Coupons</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="coupon-modal-body">
        ...
      </div>
    </div>
  </div>
</div>

@push('component-scripts')
  <script>
    $(document).on('click', '#remove-coupon-btn', function() {
      $.ajax({
        url: "{{ route('checkout.coupon.remove') }}",
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}"
        },
        success: function(response) {
          if (response.success) {
            iziNotify("", response.message, "success");
            $('#coupon-section').html('');
            $('#coupon-form').removeClass('d-none').addClass('d-flex');
            $('#coupon_code').val('');
            location.reload();
          } else {
            iziNotify("Oops!", response.message, "error");
          }
        },
        error: function() {
          iziNotify("Oops!", "Something went wrong!", "error");
        }
      });
    });

    $('#coupon-form').validate({
      rules: {
        coupon_code: {
          required: true
        }
      },
      messages: {
        coupon_code: {
          required: "Please enter a coupon code."
        }
      },
      errorElement: "span",
      errorPlacement: function(error, element) {
        if (element.attr('id') === 'coupon_code') {
          element.closest('.stock-delivery').after(error);
        } else {
          error.insertAfter(element);
        }
      },
      submitHandler: function(form) {
        let code = $('#coupon_code').val();
        let orderAmount = $('#order_amount').val();
        applyCoupon(code, orderAmount);
      }
    });

    function applyCoupon(code, orderAmount) {
      $.ajax({
        url: "{{ route('checkout.coupon.apply') }}",
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          coupon_code: code,
          order_amount: orderAmount
        },
        success: function(response) {
          if (response.success) {
            iziNotify("", response.message, "success");
            $('#couponModal').modal('hide');
            $('#coupon-section').html(response.html);
            $('#coupon-form').addClass('d-none');
            $('.order-total-amount').text(response.final_amount);
            $('#coupon_id').val(response.coupon_id);
            $('#coupon_discount').val(response.discount);
          } else {
            iziNotify("Oops!", response.message, "error");
          }
        },
        error: function() {
          iziNotify("Oops!", "Something went wrong!", "error");
        }
      });
    }

    function showCouponModal() {
      $('#couponModal').appendTo('body').modal('show');
      $.ajax({
        url: "{{ route('checkout.list-of-coupons') }}",
        type: "GET",
        success: function(response) {
          $('#couponModal #coupon-modal-body').empty();
          if (response.data.length > 0) {
            let htmlContent = '<div class="row row-cols-1 g-3" style="max-height: 400px; overflow-y: auto;">';

            $.each(response.data, function(index, coupon) {
              htmlContent += `
            <div class="col">
              <div class="card border-info shadow-sm h-100 apply-coupon-card" data-code="${coupon.code}" style="cursor: pointer;">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title mb-0 text-dark">
                      <i class="ri-coupon-3-line me-1"></i> ${coupon.code}
                    </h5>
                    <span class="badge bg-dark fs-6">
                     ${coupon.amount} OFF
                    </span>
                  </div>
                  ${coupon.min_order_value !== 0
                    ? `<p class="card-text text-muted mb-0">
                                                                                                             <i class="ri-shopping-basket-line me-1"></i> Min Order: ₹${coupon.min_order_value}
                                                                                                           </p>`
                    : ''
                  }
                </div>
              </div>
            </div>
          `;
            });

            htmlContent += '</div>';
            $('#coupon-modal-body').html(htmlContent);

            // Attach click event to each coupon card
            $('.apply-coupon-card').on('click', function() {
              const code = $(this).data('code');
              const orderAmount = $('#order_amount').val();
              applyCoupon(code, orderAmount);
            });

          } else {
            $('#coupon-modal-body').html('<p class="text-warning">No coupons available at this time.</p>');
          }
        },
        error: function() {
          $('#couponModal #coupon-modal-body').html('<p class="text-danger">Failed to load coupon data.</p>');
          iziNotify("Oops!", "Something went wrong!", "error");
        }
      });
    }
  </script>
@endpush
