@props([])
<div class="individual_blocks flow-rootX paymentMmethod">
  <h2 class="fw-normal m-0 font25 c--blackc">Select Payment Method</h2>
  <div class="suggested_accordionwrap flow-rootX">
    <h4 class="fw-normal m-0 font18 c--blackc">Suggested For You</h4>
    <form class="flow-rootx">

      @if (!empty($siteSettings['payment_gateway']))
        @if ($siteSettings['payment_gateway'] == 1)
          <div class="form-group">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="paymentmode" id="stripe"
                value="{{ 'P' . Hashids::encode(1) }}">
              <label class="form-check-label" for="stripe">Pay Via Stripe</label>
            </div>
            <div class="icon" style="top: 8px;">
              <img src="{{ asset('public/common/images/gateway_logos/stripe.png') }}" class="img-fluid" alt="Stripe"
                title="Stripe" style="height: 50px;">
            </div>
          </div>
        @elseif($siteSettings['payment_gateway'] == 2)
          <div class="form-group">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="paymentmode" id="paypal"
                value="{{ 'P' . Hashids::encode(2) }}">
              <label class="form-check-label" for="paypal">Pay Via Paypal</label>
            </div>
            <div class="icon" style="top: 8px;">
              <img src="{{ asset('public/common/images/gateway_logos/paypal.png') }}" class="img-fluid" alt="paypal"
                title="Paypal" style="height: 50px;">
            </div>
          </div>
          @endif
      @endif

      <div class="form-group">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="paymentmode" id="cod"
            value="{{ 'P' . Hashids::encode(0) }}" checked>
          <label class="form-check-label" for="cod">Cash On Delivery</label>
        </div>
        <div class="icon" style="top: 8px;">
          <img src="{{ asset('public/common/images/gateway_logos/cod.png') }}" class="img-fluid" alt="Cash on Delivery"
            title="Cash on Delivery" style="height: 50px;">
        </div>
      </div>
    </form>
  </div>
</div>
