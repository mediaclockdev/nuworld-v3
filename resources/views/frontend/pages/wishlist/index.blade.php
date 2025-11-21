@extends('frontend.layouts.app')
@section('title', @$title)
@section('content')
  <section class="breadcrumb-wrapper py-4 border-top">
    <div class="container-xxl">
      <ul class="breadcrumbs">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li>Wishlist & Collection</li>
      </ul>
    </div>
  </section>
  <section class="furniture__wishlist_wrap pb-gutter">
    <div class="container-xxl flow-rootX3">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="font45 fw-normal c--blackc">Wishlist <span class="c--gry">{{ $saved_for_later_items->count() }}
              items</span></h1>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="furniture--grid-4 y-axis mt-3 all-product-card-item">
            @forelse ($saved_for_later_items as $key=>$item)
              <div class="product-card" id="product-card-item-{{ $item->product_variant_id }}">
                @php
                  $isDiscount =
                      isset($item->productVariant->sale_price) &&
                      $item->productVariant->sale_price < $item->productVariant->regular_price;
                @endphp
                <form id="add-to-cart-form-{{ $item->product_variant_id }}" action="{{ route('cart.add') }}"
                  method="POST" style="display: none;">
                  @csrf
                  <input type="hidden" name="product_variant_id" value="{{ Hashids::encode($item->product_variant_id) }}">
                  <input type="hidden" name="quantity" value="1">
                  <input type="hidden" name="is_saved_for_later" value="0">
                  <input type="hidden" name="action" value="add_to_cart">
                </form>
                <form id="remove-form-{{ $item->product_variant_id }}" action="{{ route('cart.remove') }}" method="POST"
                  style="display: none;">
                  @csrf
                  <input type="hidden" name="product_variant_id" value="{{ Hashids::encode($item->product_variant_id) }}">
                </form>
                <div class="main-image-wrap border">
                  <div class="{{ isInCart($item->product_variant_id) ? 'd-none' : 'showingbag' }}">
                    <a href="javascript:void();" title="Remove"
                      onclick="event.preventDefault(); document.getElementById('remove-form-{{ $item->product_variant_id }}').submit();"><span
                        class="material-symbols-outlined">close</span></a>

                    @if ($item->productVariant?->inventory?->quantity > 0)
                      <a href="javascript:void(0)"
                        class="{{ isInCart($item->product_variant_id) ? '' : 'add-to-cart-btn' }}"
                        data-id="{{ Hashids::encode($item->product_variant_id) }}" data-serial="{{ $item->product_variant_id }}" title="Add To Cart"
                        @if (isInCart($item->product_variant_id)) style="pointer-events: none; opacity: 0.4;" @endif>
                        <span class="material-symbols-outlined">local_mall</span>
                      </a>
                    @endif

                  </div>
                  <div class="main-images ratio ratio-1000x800">
                    <img data-color="{{ $productVariantData[$key]->value ?? '' }}" class="active" alt=""
                      src="{{ !empty($productVariantData[$key]->file_name ?? '') ? asset('public/storage/uploads/media/products/images/' . $productVariantData[$key]->file_name) : asset('public/backend/assetss/images/products/product_thumb.jpg') }}" />
                    {{-- <img data-color="pink" src="{{ asset('public/frontend/assets/img/product/po2.png') }}" alt="pink" />
                            <img data-color="yellow" src="{{ asset('public/frontend/assets/img/product/po3.png') }}" alt="yellow" />
                            <img data-color="black" src="{{ asset('public/frontend/assets/img/product/po4.png') }}" alt="black" /> --}}
                  </div>
                  @if ($item->productVariant?->inventory?->quantity < 1)
                    <div class="outofstockcard">
                      <div class="btn btn-light font18 c--primary">Out Of Stock</div>
                    </div>
                  @endif
                </div>
                <div class="product-details flow-rootx2">
                  <div class="d-flex gap-3 justify-content-between align-items-center">
                    <div class="category m-0">
                      <h4 class="fw-normal"><a
                          href="{{ route('search.product', \Illuminate\Support\Str::slug($item->productVariant->product->name)) }}"
                          title="Sofa" class="font14">{{ $item->productVariant->product->name ?? '' }}</a></h4>
                    </div>
                    {{--
                            <div class="color-option">
                            <div class="circles">
                                <span class="circle {{ $key == 0 ? 'active' : '' }}" data-color="{{ $productVariantData[$key]['value'] }}" data-hex="{{ $productVariantData[$key]['value_details'] }}"></span>
                            </div>
                            </div>
                            --}}
                  </div>
                  <div class="product_name">
                    <h3 class="font18 fw-normal"><a href="{{ route('product.show', $item->productVariant->sku) }}"
                        title="Sofa">{{ $item->productVariant->name ?? '' }}</a></h3>
                  </div>
                  {{ displayPrice($item->productVariant->sale_price ?? $item->productVariant->regular_price) }}
                  @if ($isDiscount)
                    <span class="old-price ms-2">{{ displayPrice($item->productVariant->regular_price) }}</span>
                  @endif
                </div>
              </div>
            @empty
              <h2 class="c--gry">No item exists</h2>
            @endforelse

          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Modal -->
  <div class="modal genericmodal right fade" id="sidefilter" tabindex="-1" aria-labelledby="sidefilterLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="font20 fw-medium m-0" id="myModalLabel2">Filters</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="filter_ins_wrp">
            <div class="accordion accordion-flush" id="sidefilteraccord">
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingOne">
                  <button class="accordion-button ps-0 pe-0 text-uppercase font16 collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false"
                    aria-controls="flush-collapseOne">Price Range</button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                  data-bs-parent="#sidefilteraccord">
                  <div class="accordion-body">
                    <div class="price_range">
                      <div class="price-input">
                        <div class="field">
                          <span>Min</span>
                          <input type="number" class="input-min" value="2500">
                        </div>
                        <div class="separator">-</div>
                        <div class="field">
                          <span>Max</span>
                          <input type="number" class="input-max" value="7500">
                        </div>
                      </div>
                      <div class="sliders">
                        <div class="progress"></div>
                      </div>
                      <div class="range-input">
                        <input type="range" class="range-min" min="0" max="10000" value="2500"
                          step="100">
                        <input type="range" class="range-max" min="0" max="10000" value="7500"
                          step="100">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingTwo">
                  <button class="accordion-button ps-0 pe-0 text-uppercase font16 collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false"
                    aria-controls="flush-collapseTwo">Material</button>
                </h2>
                <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo"
                  data-bs-parent="#sidefilteraccord">
                  <div class="accordion-body">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="sortby" id="recommended">
                      <label class="form-check-label" for="recommended">Recommended</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="sortby" id="pricelowhigh" checked>
                      <label class="form-check-label" for="pricelowhigh">Price (Low to High)</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="sortby" id="pricehighlow">
                      <label class="form-check-label" for="pricehighlow">Price (High to Low)</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="sortby" id="latest">
                      <label class="form-check-label" for="latest">Latest</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="sortby" id="fastshipping">
                      <label class="form-check-label" for="fastshipping">Fast Shipping</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingThree">
                  <button class="accordion-button ps-0 pe-0 text-uppercase font16 collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false"
                    aria-controls="flush-collapseThree">Designs</button>
                </h2>
                <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree"
                  data-bs-parent="#sidefilteraccord">
                  <div class="accordion-body">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="designs" id="recommended1">
                      <label class="form-check-label" for="recommended1">Recommended</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="designs" id="pricelowhigh2" checked>
                      <label class="form-check-label" for="pricelowhigh2">Price (Low to High)</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="designs" id="pricehighlow3">
                      <label class="form-check-label" for="pricehighlow3">Price (High to Low)</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="designs" id="latest4">
                      <label class="form-check-label" for="latest4">Latest</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="designs" id="fastshipping4">
                      <label class="form-check-label" for="fastshipping4">Fast Shipping</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingFour">
                  <button class="accordion-button ps-0 pe-0 text-uppercase font16 collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false"
                    aria-controls="flush-collapseFour">Color</button>
                </h2>
                <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour"
                  data-bs-parent="#sidefilteraccord">
                  <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate
                    the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more
                    exciting happening here in terms of content, but just filling up the space to make it look, at least
                    at first glance, a bit more representative of how this would look in a real-world application.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingFive">
                  <button class="accordion-button ps-0 pe-0 text-uppercase font16 collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false"
                    aria-controls="flush-collapseFive">Shapes</button>
                </h2>
                <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive"
                  data-bs-parent="#sidefilteraccord">
                  <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate
                    the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more
                    exciting happening here in terms of content, but just filling up the space to make it look, at least
                    at first glance, a bit more representative of how this would look in a real-world application.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingSix">
                  <button class="accordion-button ps-0 pe-0 text-uppercase font16 collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false"
                    aria-controls="flush-collapseSix">Storage</button>
                </h2>
                <div id="flush-collapseSix" class="accordion-collapse collapse" aria-labelledby="flush-headingSix"
                  data-bs-parent="#sidefilteraccord">
                  <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate
                    the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more
                    exciting happening here in terms of content, but just filling up the space to make it look, at least
                    at first glance, a bit more representative of how this would look in a real-world application.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingSeven">
                  <button class="accordion-button ps-0 pe-0 text-uppercase font16 collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven" aria-expanded="false"
                    aria-controls="flush-collapseSeven">Finish</button>
                </h2>
                <div id="flush-collapseSeven" class="accordion-collapse collapse" aria-labelledby="flush-headingSeven"
                  data-bs-parent="#sidefilteraccord">
                  <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate
                    the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more
                    exciting happening here in terms of content, but just filling up the space to make it look, at least
                    at first glance, a bit more representative of how this would look in a real-world application.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingEight">
                  <button class="accordion-button ps-0 pe-0 text-uppercase font16 collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#flush-collapseEight" aria-expanded="false"
                    aria-controls="flush-collapseEight">Brands</button>
                </h2>
                <div id="flush-collapseEight" class="accordion-collapse collapse" aria-labelledby="flush-headingEight"
                  data-bs-parent="#sidefilteraccord">
                  <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate
                    the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more
                    exciting happening here in terms of content, but just filling up the space to make it look, at least
                    at first glance, a bit more representative of how this would look in a real-world application.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingNine">
                  <button class="accordion-button ps-0 pe-0 text-uppercase font16 collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#flush-collapseNine" aria-expanded="false"
                    aria-controls="flush-collapseNine">Discounts</button>
                </h2>
                <div id="flush-collapseNine" class="accordion-collapse collapse" aria-labelledby="flush-headingNine"
                  data-bs-parent="#sidefilteraccord">
                  <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate
                    the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more
                    exciting happening here in terms of content, but just filling up the space to make it look, at least
                    at first glance, a bit more representative of how this would look in a real-world application.</div>
                </div>
              </div>
            </div>
          </div>
          <div class="filter_action d-flex justify-content-end align-items-center gap-3">
            <a class="btn btn-outline-dark w-50 py-3" href="javascript:void();" title="Clear All">Clear All</a>
            <a class="btn btn-dark w-50 py-3" href="javascript:void();" title="Apply">Apply</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end Modal -->
@endsection
@push('scripts')
@endpush
