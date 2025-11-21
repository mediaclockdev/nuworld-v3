@props([
    'productCategories' => [],
    'category' => null,
])

<div class="row">
  <div class="col-lg-12">
    <div class="product_range_wrap">
      <h4 class="font25 fw-normal m-0">Categories</h4>
      <div class="range_swiper">
        <div class="swiper swiper__5">
          <div class="swiper-wrapper eq-height">
            @if ($productCategories->count() > 0)
              @foreach ($productCategories as $productCategory)
                @php
                  // Use the first product's default image if available, otherwise fallback to variant gallery or default image
                  /* $defaultImage =
                      $productCategory->products->first()->default_image ??
                      ($productCategory->products->first()->variants->first()->galleries->first()->file_name ?? null)
; */

                  $defaultImage = $productCategory->category_image ?? null;
                  $productCount = $productCategory->products->flatMap->variants->count();
                @endphp
                <div class="swiper-slide">
                  <div class="product_range_box {{ $category?->slug === $productCategory->slug ? 'active' : '' }}"
                    data-category="{{ $productCategory->slug }}">
                    <a href="{{ route('category.list', $productCategory->slug) }}" class="group-filter"
                      data-category="{{ $productCategory->slug }}"></a>
                    <figure class="m-0 ratio ratio-1000x800">
                      <img
                        src="{{ $defaultImage ? asset('public/storage/uploads/categories/' . $defaultImage) : asset('public/backend/assetss/images/products/product_thumb.jpg') }}"
                        alt="Product Image" title="Product Image" />
                    </figure>
                    <div class="inf">
                      <p class="font14 m-0">View All. {{ $productCategory->title }}</p>
                      <p class="font14 m-0"><strong>{{ $productCount }}</strong> options</p>
                    </div>
                  </div>
                </div>
              @endforeach
            @endif
          </div>
        </div>
      </div>
      <div class="swiper-nav-inline">
        <div class="swipper_button swiper__5--prev">
          <span class="material-symbols-outlined c--blackc">arrow_back_ios_new</span>
        </div>
        <div class="swipper_button swiper__5--next">
          <span class="material-symbols-outlined c--blackc">arrow_forward_ios</span>
        </div>
      </div>
    </div>
  </div>
</div>
