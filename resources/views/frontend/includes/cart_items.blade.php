@if ($items->count())
  @foreach ($items as $item)
    <div class="cart_page_block border" id="product-cart-item-{{ $item->productVariant->id }}">
      <div class="product_thumb">
        <figure class="m-0 ratio ratio-1000x800">
          <a href="{{ route('product.show', $item->productVariant->sku) }}"
            title="{{ $item->productVariant->name ?? '' }}">
            <img
              src="{{ !empty($item->productVariant->galleries[0]['file_name'])
                  ? asset('public/storage/uploads/media/products/images/' . $item->productVariant->galleries[0]['file_name'])
                  : asset('public/backend/assetss/images/products/product_thumb.jpg') }}"
              alt="{{ $item->productVariant->name ?? '' }}" class="imageFit">
          </a>
        </figure>
      </div>

      <div class="product_info border-start d-flex justify-content-center flex-column p-4 flow-rootX2">

        <div class="cart-action d-flex justify-content-end align-items-center gap-3">
          <a href="#" class="action_remove c--blackc d-flex align-items-center gap-2 "
            onclick="event.preventDefault(); document.getElementById('{{ 'remove-form' }}-{{ $item->product_variant_id }}').submit()">
            <span class="material-symbols-outlined font20">delete</span>
            <u>Remove</u>
          </a>
          <form id="remove-form-{{ $item->product_variant_id }}" action="{{ route('cart.remove') }}" method="POST"
            style="display: none;">
            @csrf
            <input type="hidden" name="product_variant_id" value="{{ Hashids::encode($item->product_variant_id) }}">
          </form>

          @if (auth()->check())
            <a href="javascript:void(0);" data-id="{{ Hashids::encode($item->product_variant_id) }}"
              data-serial="{{ $item->product_variant_id }}"
              class="action_savelater add-to-wishlist-btn c--blackc d-flex align-items-center gap-2">
              <span class="material-symbols-outlined font20"> update</span>
              <u>Move to Wishlist</u>
            </a>
            {{-- -- Add to WISHLIST Form -- --}}
            <form id="add-to-wishlist-form-{{ $item->productVariant->id }}" action="{{ route('cart.add') }}"
              method="POST" style="display: none;">
              @csrf
              <input type="hidden" name="product_variant_id" value="{{ Hashids::encode($item->productVariant->id) }}">
              <input type="hidden" name="quantity" value="1">
              <input type="hidden" name="is_saved_for_later" value="1">
              <input type="hidden" name="action" value="add_to_wishlist">
            </form>
          @endif

        </div>
        @php
          $variantName = trim($item->productVariant->name ?? '');

          // Normalize extra spaces and casing
          $variantName = preg_replace('/\s+/', ' ', $variantName);

          // Split by space
          $parts = explode(' ', $variantName);

          // Define what units might appear at the end
          $unitPattern = '/^(?:\d+(\.\d+)?\s?(?:g|kg|ml|l|ltr|litre|litres|pcs|piece|pack))$/i';

          $selectedVariant = '';
          $baseName = $variantName;

          // If the last word matches a unit pattern → treat as variant
          if (!empty($parts) && preg_match($unitPattern, end($parts))) {
              $selectedVariant = strtoupper(array_pop($parts));
              $baseName = trim(implode(' ', $parts));
          }
        @endphp
        <div class="product_name_category flow-rootx">
          <a style="all: unset; cursor: pointer;" href="{{ route('product.show', $item->productVariant->sku) }}">
            <h5 class="font35 fw-normal c--blackc mt-0">{{ $item->productVariant->name ?? '' }}</h5>
            {{-- <h4 class="font20 fw-normal c--blackc mb-0">Variant Selected : {{ $selectedVariant ?? 'Unknown Variant' }} --}}
            </h4>
          </a>
        </div>

        <x-product-price :variant="$item->productVariant" />

        @if ($cart_action)
          <div class="cart-itemadd-less d-flex justify-content-end mt-2">
            @if ($errors->has('quantity.' . $item->product_variant_id))
              <div class="error pt-3 me-2">
                {{ $errors->first('quantity.' . $item->product_variant_id) }}
              </div>
            @endif
            @if ($errors->has('cart'))
              <div class="error pt-3 me-2">
                {{ $errors->first('cart') }}
              </div>
            @endif

            @php
              $display_quantity = $display_quantity ?? false;
            @endphp
            @if ($display_quantity)
              @php
                $decrementCondition = $item->quantity <= 1;
                $incrementCondition = $item->quantity >= $item->productVariant->inventory->quantity;
              @endphp

              <form id="decrement-form-{{ $item->product_variant_id }}" action="{{ route('cart.updateQuantity') }}"
                method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="product_variant_id"
                  value="{{ Hashids::encode($item->product_variant_id) }}">
                <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
              </form>

              <form id="increment-form-{{ $item->product_variant_id }}" action="{{ route('cart.updateQuantity') }}"
                method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="product_variant_id"
                  value="{{ Hashids::encode($item->product_variant_id) }}">
                <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
              </form>

              {{-- Quantity display with buttons --}}
              <div class="number_product">
                @php
                  $quantity = $item->quantity ?? 1;
                @endphp
                <input type="text" value="{{ $quantity }}" readonly />

                {{-- Decrement --}}
                <span class="minus">
                  <span class="material-symbols-outlined {{ $decrementCondition ? 'text-muted disabled' : '' }}"
                    style="cursor: {{ $decrementCondition ? 'not-allowed' : 'pointer' }}; color: {{ $decrementCondition ? 'red' : '' }}"
                    onclick="{{ !$decrementCondition ? "event.preventDefault(); document.getElementById('decrement-form-{$item->product_variant_id}').submit()" : '' }}">
                    keyboard_arrow_down
                  </span>
                </span>

                {{-- Increment --}}
                <span class="plus">
                  <span class="material-symbols-outlined"
                    onclick="event.preventDefault(); document.getElementById('increment-form-{{ $item->product_variant_id }}').submit()">
                    keyboard_arrow_up
                  </span>
                </span>
              </div>
            @else
              <div class="number_product d-flex align-items-center">
                X {{ $item->quantity }}
              </div>
            @endif
          </div>
        @else
          <div class="cart-item-quantity d-flex align-items-center justify-content-end mt-2">
            <span class="text-muted fs-4">X {{ $item->quantity }}</span>
          </div>
        @endif
      </div>
    </div>
  @endforeach
@else
  <div class="text-center border p-5">
    <h4>Your cart is empty.</h4>
  </div>
@endif
