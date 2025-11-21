{{-- @if ($order['order_status'] == 5) --}}
<div class="rate_write pt-4">
  <input type="hidden" id="order_id" value="{{ $item->variant->id }}">
  <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#WriteReviewModal" title="Rate & Write Review">
    <div class="starwrp d-flex justify-content-start align-items-center gap-3">
      <div class="stars">
        <ion-icon class="star" id="star1" name="star-outline"></ion-icon>
        <ion-icon class="star" id="star2" name="star-outline"></ion-icon>
        <ion-icon class="star" id="star3" name="star-outline"></ion-icon>
        <ion-icon class="star" id="star4" name="star-outline"></ion-icon>
        <ion-icon class="star" id="star5" name="star-outline"></ion-icon>
      </div>
      <p class="font16 c--primary mb-0">Rate & Write Review</p>
    </div>
  </a>
</div>
{{-- @endif --}}
