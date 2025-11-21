@if ($items->count())
  @foreach ($items as $item)
    <div class="cart_page_block border" id="product-cart-item-{{ $item->productVariant->id }}">
      <div class="product_thumb">
        <figure class="m-0 ratio ratio-1000x800">
          <a href="{{ route('product.show', $item->productVariant->sku) }}" title="{{ $item->productVariant->name }}">
            <img
              src="{{ !empty($item->productVariant->galleries[0]['file_name'])
                  ? asset('public/storage/uploads/media/products/images/' . $item->productVariant->galleries[0]['file_name'])
                  : asset('public/backend/assetss/images/products/product_thumb.jpg') }}"
              alt="{{ $item->productVariant->name }}" class="imageFit">
          </a>
        </figure>
      </div>

      <div class="product_info border-start d-flex justify-content-center flex-column p-4 flow-rootX2">

          <div class="cart-action d-flex justify-content-end align-items-center gap-3">
            <a href="javascript:void(0);" data-id="{{ Hashids::encode($item->productVariant->id) }}" data-serial="{{ $item->productVariant->id }}" class="action_savelater add-to-cart-btn c--blackc d-flex align-items-center gap-2">
                <span class="material-symbols-outlined font20"> update</span>
                <u>Move to Cart</u>
            </a>
            {{-- -- Add to Cart Form -- --}}
            <form id="add-to-cart-form-{{ $item->product_variant_id }}" action="{{ route('cart.add') }}" method="POST" style="display: none;">
              @csrf
              <input type="hidden" name="product_variant_id" value="{{ Hashids::encode($item->product_variant_id) }}">
              <input type="hidden" name="quantity" value="{{ $item->quantity }}">
              <input type="hidden" name="is_saved_for_later" value="0">
              <input type="hidden" name="action" value="add_to_cart">
            </form>
          </div>
        <div class="product_name_category flow-rootx">
          <a style="all: unset; cursor: pointer;" href="{{ route('product.show', $item->productVariant->sku) }}">
            <h5 class="font35 fw-normal c--blackc mt-0">{{ $item->productVariant->name }}</h5>
          </a>
        </div>
        <x-product-price :variant="$item->productVariant" />
      </div>
  </div>
  @endforeach
@else
  <div class="text-center border p-5">
    <h4>No items in wishlist.</h4>
  </div>
@endif
