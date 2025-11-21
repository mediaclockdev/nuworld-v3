<div class="added_cart d-flex justify-content-between align-items-center gap-3 py-2">
  <p class="font18 m-0">
    Coupon Discount <span class="font14 c--success">{{ $couponCode }} (Applied)</span>
  </p>
  <div class="d-flex align-items-center gap-3">
    <p class="font18 m-0 c--success">-{{ displayPrice($discount) }}</p>
    <button type="button" id="remove-coupon-btn" class="btn btn-sm btn-outline-danger">Remove</button>
  </div>
</div>
  