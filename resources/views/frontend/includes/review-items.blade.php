@php
  $rating = $review->rating ?? 0;
  $isEdit = isset($review->id);
@endphp

{{-- <div class="rate_write pt-4">
  <input type="hidden" id="order_id" value="{{ $variantId }}">
  <a href="javascript:void(0);" class="open-review-modal {{ $orderStatus != 5 ? 'disabled-link' : '' }}"
    data-bs-toggle="modal" data-bs-target="#WriteReviewModal" data-variant-id="{{ $variantId }}"
    data-variant-name="{{ $variantName }}" data-review-id="{{ $review->id ?? '' }}"
    data-review-productreview="{{ $review->productreview ?? '' }}" data-image="{{ $image }}"
    title="Rate & Write Review">

    <div class="starwrp d-flex justify-content-start align-items-center gap-3">
      <div class="stars disbled">
        @for ($i = 1; $i <= 5; $i++)
          <ion-icon class="star{{ $i <= $rating ? ' active' : '' }}" id="star{{ $i }}"
            name="{{ $i <= $rating ? 'star' : 'star-outline' }}"></ion-icon>
        @endfor
      </div>

      @if ($orderStatus == 5)
        <p class="font16 c--primary mb-0">
          {{ $isEdit ? 'Edit Review' : 'Rate & Write Review' }}
        </p>
      @endif
    </div>
  </a>
</div> --}}
@if ($orderStatus == 5)
  <div class="rate_write pt-4">
    <input type="hidden" id="order_id" value="{{ $variantId }}">
    <a href="javascript:void(0);" class="open-review-modal" data-bs-toggle="modal" data-bs-target="#WriteReviewModal"
      data-variant-id="{{ $variantId }}" data-variant-name="{{ $variantName }}"
      data-review-id="{{ $review->id ?? '' }}" data-review-productreview="{{ $review->productreview ?? '' }}"
      data-image="{{ $image }}" title="Rate & Write Review">

      <div class="starwrp d-flex justify-content-start align-items-center gap-3">
        <div class="stars disbled">
          @for ($i = 1; $i <= 5; $i++)
            <ion-icon class="star{{ $i <= $rating ? ' active' : '' }}" id="star{{ $i }}"
              name="{{ $i <= $rating ? 'star' : 'star-outline' }}"></ion-icon>
          @endfor
        </div>

        @if ($orderStatus == 5)
          <p class="font16 c--primary mb-0">
            {{ $isEdit ? 'Edit Review' : 'Rate & Write Review' }}
            
          </p>
        @endif
      </div>
    </a>
  </div>
@endif
