<section class="furniture_category__Slide pt-0">
  <div class="container">
    <div class="row">
      {{-- @php

        pd($categoryHeadlineBanner);
      @endphp --}}
      <div class="col-lg-12 py-5 my-5">
        <h2 class="font80 fw-normal c--blackc text-center my-5"> {!! $categoryHeadlineBanner->title ?? 'Ingenious design meets<br>lasting durability' !!}</h2>
        {{-- {!! $settings['content'] ?? '' !!} --}}
      </div>
    </div>
  </div>
  <div class="container-xxl">
    <div class="row flow-rootX3">
      <div class="col-lg-12">
        <div class="d-flex justify-content-between align-center gap-3">
          <h2 class="font35 fw-normal c--blackc m-0">Shop by Category</h2>
          <div class="swiper-nav-inline d-flex justify-content-between align-center gap-3">
            <div class="swipper_button d-flex swiper__4--prev"><span
                class="material-symbols-outlined font35 c--blackc">arrow_back_ios_new</span></div>
            <div class="swipper_button d-flex swiper__4--next"><span
                class="material-symbols-outlined font35 c--blackc">arrow_forward_ios</span></div>
          </div>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="swiperwrp">
          <div class="swiper swiper__4">
            <div class="swiper-wrapper eq-height">
              @foreach ($categories as $category)
                @php $defaultImage = $category->category_image ?? null; @endphp
                <div class="swiper-slide">
                  <div class="category-card">
                    @if ($category->slug == 'ground-goat')
                      <a href="#" title="{{ $category->title }}"></a>
                    @else
                      <a href="{{ route('category.slug', $category->slug) }}" title="{{ $category->title }}"></a>
                    @endif
                    <figure class="m-0 imageTrans ratio ratio-1000x1000"><img
                        src="{{ $defaultImage ? asset('public/storage/uploads/categories/' . $defaultImage) : asset('public/backend/assetss/images/products/product_thumb.jpg') }}"
                        alt="Mayuri" title="Mayuri" />
                    </figure>
                    <h3 class="fw-medium c--blackc font25">{{ $category->title }}</h3>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
