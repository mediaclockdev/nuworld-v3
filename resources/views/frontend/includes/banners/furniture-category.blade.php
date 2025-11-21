{{-- <section class="furniture_category_threeblocks">
  <div class="container-fluid">
    <div class="row">
      @php $count = 0; @endphp

      @forelse ($furnitureCategories as $item)
        @php
          $count++;
          $banner = $item['furnitureCategoryBanner'];
          $variant = $item['furnitureCategoryProductVariant'];
          $bannerSettings = json_decode($banner['settings'] ?? '{}', true);
        @endphp

        <div class="{{ $count == 2 ? 'col-lg-6' : 'col-lg-3' }}">
          <div class="product-with-blur-content">
            <figure>
              <img
                src="{{ !empty($bannerSettings['image']) ? asset(config('defaults.banner_image_path') . $bannerSettings['image']) : asset('public/frontend/assets/img/home/category_threeblocks_th1.png') }}"
                alt="{{ $bannerSettings['alt_text'] ?? '' }}" />
            </figure>
            <div class="info">
              <div class="inside flow-rootX c--blackc">
                @if ($variant)
                  @php
                    $priceData = findSalePrice($variant['id']);
                  @endphp

                  <div class="top flow-rootx2 c--blackc">
                    <h3 class="font30 fw-normal text-center">{{ $variant['name'] }}</h3>
                  </div>

                  <div class="pricebox font20 text-center">
                    @if ($priceData)
                      @if (!$priceData['regular_price_true'])
                        <span>{{ displayPrice($priceData['display_price']) }}</span>
                      @else
                        <span>{{ displayPrice($priceData['regular_price']) }}</span>
                      @endif
                    @endif
                  </div>
                @else
                  {!! $bannerSettings['content'] ?? '' !!}
                @endif

                <a href="{{ $bannerSettings['hyper_link'] ?? ($variant && $variant['sku'] ? route('product.show', $variant['sku']) : '#') }}"
                  class="btn btn-outline-dark px-4">
                  {{ $bannerSettings['btn_text'] ?? 'View' }}
                </a>
              </div>
            </div>
          </div>
        </div>

      @empty

        <div class="col-lg-3">
          <div class="product-with-blur-content">
            <figure><img src="{{ asset('public/frontend/assets/img/home/category_threeblocks_th1.png') }}"
                alt="Mayuri" title="Mayuri" /></figure>
            <div class="info">
              <div class="inside flow-rootX2 c--blackc">
                <div class="top flow-rootx2 c--blackc pb-3">
                  <h4 class="font16 fw-normal text-center mb-0">New Arrival</h4>
                  <h3 class="font35 fw-normal text-center">Chair</h3>
                </div>
                <div class="pricebox font25 text-center"><span>$</span>750.00</div>
                <a href="javascript:void();" class="btn btn-outline-dark px-4 py-2" title="View">View</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="product-with-blur-content">
            <figure><img src="{{ asset('public/frontend/assets/img/home/category_threeblocks_th2.png') }}"
                alt="Mayuri" title="Mayuri" /></figure>
            <div class="info">
              <div class="inside flow-rootX2 c--blackc">
                <div class="top flow-rootx2 c--blackc pb-3">
                  <h4 class="font16 fw-normal text-center mb-0">New Arrival</h4>
                  <h3 class="font35 fw-normal text-center">Coffee Table</h3>
                </div>
                <div class="pricebox font25 text-center"><span>$</span>750.00</div>
                <a href="javascript:void();" class="btn btn-outline-dark px-4 py-2" title="View">View</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3">
          <div class="product-with-blur-content">
            <figure><img src="{{ asset('public/frontend/assets/img/home/category_threeblocks_th3.png') }}"
                alt="Mayuri" title="Mayuri" /></figure>
            <div class="info">
              <div class="inside flow-rootX2 c--blackc">
                <div class="top flow-rootx2 c--blackc pb-3">
                  <h4 class="font16 fw-normal text-center mb-0">New Arrival</h4>
                  <h3 class="font35 fw-normal text-center">Chair</h3>
                </div>
                <div class="pricebox font25 text-center"><span>$</span>750.00</div>
                <a href="javascript:void();" class="btn btn-outline-dark px-4 py-2" title="View">View</a>
              </div>
            </div>
          </div>
        </div>
      @endforelse
    </div>
  </div>
</section> --}}


<section class="country_category_blocks">
  <div class="container-xxl">
    <div class="row">
      <div class="col-12">
        <div class="country_category_inner">
          <a href="javascript:void(0);" class="d-flex align-items-center"><img
              src="{{ asset('public/frontend/assets/img/category/country/india-flag.png') }}" alt="Mayuri"
              title="Mayuri" /> Indian Grocery</a>
          <a href="javascript:void(0);" class="d-flex align-items-center"><img
              src="{{ asset('public/frontend/assets/img/category/country/pak-flag.png') }}" alt="Mayuri"
              title="Mayuri" /> Pakistani Grocery</a>
          <a href="javascript:void(0);" class="d-flex align-items-center"><img
              src="{{ asset('public/frontend/assets/img/category/country/srilanka-flag.png') }}" alt="Mayuri"
              title="Mayuri" /> Srilankan Grocery</a>
          <a href="javascript:void(0);" class="d-flex align-items-center"><img
              src="{{ asset('public/frontend/assets/img/category/country/nepal-flag.png') }}" alt="Mayuri"
              title="Mayuri" /> Nepali Grocery</a>
          <a href="javascript:void(0);" class="d-flex align-items-center"><img
              src="{{ asset('public/frontend/assets/img/category/country/bangladesh-flag.png') }}" alt="Mayuri"
              title="Mayuri" /> Bangladeshi Grocery</a>
        </div>
      </div>
    </div>

  </div>
</section>

<section class="furniture_category_threeblocks">
  <div class="category_threeblocks_inner">
    <div class="catg_col col1">
      <a href="javascript:void(0);"><img src="{{ asset('public/frontend/assets/img/category/grocery/img1.jpg') }}"
          alt="Mayuri" title="Mayuri" /></a>
    </div>
    <div class="catg_col col2">
      <a href="javascript:void(0);"><img src="{{ asset('public/frontend/assets/img/category/grocery/img2.jpg') }}"
          alt="Mayuri" title="Mayuri" /></a>
    </div>
    <div class="catg_col col3">
      <a href="javascript:void(0);"><img src="{{ asset('public/frontend/assets/img/category/grocery/img3.jpg') }}"
          alt="Mayuri" title="Mayuri" /></a>
    </div>
  </div>
</section>
