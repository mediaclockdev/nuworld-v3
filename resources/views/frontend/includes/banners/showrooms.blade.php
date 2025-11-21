<section class="showroom__slider_section flow-rootX3">
  <div class="container-xxl">
    <div class="row align-items-center">
      <div class="col-8">
        <h2 class="fw-normal m-0 font45 c--blackc">Our Stores</h2>
      </div>
      <div class="col-4">
        <div class="swiper-nav-inline d-flex justify-content-end">
          <div class="swipper_button showroom--prev"><span
              class="material-symbols-outlined font35 c--blackc">arrow_back_ios_new</span></div>
          <div class="swipper_button showroom--next"><span
              class="material-symbols-outlined font35 c--blackc">arrow_forward_ios</span></div>
        </div>
      </div>
    </div>
  </div>
  <div class="container-xxl">
    <div class="swiperwrp">
      <div class="swiper showroom-slider">
        <div class="swiper-wrapper eq-height">
          @if ($stores->isNotEmpty())
            @foreach ($stores as $store)
              <div class="swiper-slide">
                <div class="showroom-card">
                  <div class="showroom-img">
                    <img
                      src="{{ !empty($store->image) ? asset('public/storage/uploads/stores/' . $store->image) : asset('public/frontend/assetss/img/default_thumb.jpg') }}"
                      alt="{{ $store->name }}" title="{{ $store->name }}" />
                  </div>
                  <div class="card-info d-flex align-items-center justify-content-between">
                    <div>
                      <h3 class="card-title">{{ $store->name ?? '' }}</h3>
                      <p class="card-action">{{ Illuminate\Support\Str::limit($store->address ?? '', 60) }}</p>
                    </div>
                    <div class="d-flex gap-2">
                      <a class="card-phone" href="{{ $store->location ?? '#' }}" aria-label="Call showroom"
                        target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="20" viewBox="0 0 16 20"
                          fill="none">
                          <path
                            d="M8 10C8.55 10 9.02083 9.80417 9.4125 9.4125C9.80417 9.02083 10 8.55 10 8C10 7.45 9.80417 6.97917 9.4125 6.5875C9.02083 6.19583 8.55 6 8 6C7.45 6 6.97917 6.19583 6.5875 6.5875C6.19583 6.97917 6 7.45 6 8C6 8.55 6.19583 9.02083 6.5875 9.4125C6.97917 9.80417 7.45 10 8 10ZM8 17.35C10.0333 15.4833 11.5417 13.7875 12.525 12.2625C13.5083 10.7375 14 9.38333 14 8.2C14 6.38333 13.4208 4.89583 12.2625 3.7375C11.1042 2.57917 9.68333 2 8 2C6.31667 2 4.89583 2.57917 3.7375 3.7375C2.57917 4.89583 2 6.38333 2 8.2C2 9.38333 2.49167 10.7375 3.475 12.2625C4.45833 13.7875 5.96667 15.4833 8 17.35ZM8 20C5.31667 17.7167 3.3125 15.5958 1.9875 13.6375C0.6625 11.6792 0 9.86667 0 8.2C0 5.7 0.804167 3.70833 2.4125 2.225C4.02083 0.741667 5.88333 0 8 0C10.1167 0 11.9792 0.741667 13.5875 2.225C15.1958 3.70833 16 5.7 16 8.2C16 9.86667 15.3375 11.6792 14.0125 13.6375C12.6875 15.5958 10.6833 17.7167 8 20Z"
                            fill="black" />
                        </svg>
                      </a>
                      <a class="card-phone" href="tel:+{{ $store->phone ?? '' }}" aria-label="Call showroom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                          fill="none">
                          <path
                            d="M16.95 18C14.8667 18 12.8083 17.5458 10.775 16.6375C8.74167 15.7292 6.89167 14.4417 5.225 12.775C3.55833 11.1083 2.27083 9.25833 1.3625 7.225C0.454167 5.19167 0 3.13333 0 1.05C0 0.75 0.1 0.5 0.3 0.3C0.5 0.1 0.75 0 1.05 0H5.1C5.33333 0 5.54167 0.0791667 5.725 0.2375C5.90833 0.395833 6.01667 0.583333 6.05 0.8L6.7 4.3C6.73333 4.56667 6.725 4.79167 6.675 4.975C6.625 5.15833 6.53333 5.31667 6.4 5.45L3.975 7.9C4.30833 8.51667 4.70417 9.1125 5.1625 9.6875C5.62083 10.2625 6.125 10.8167 6.675 11.35C7.19167 11.8667 7.73333 12.3458 8.3 12.7875C8.86667 13.2292 9.46667 13.6333 10.1 14L12.45 11.65C12.6 11.5 12.7958 11.3875 13.0375 11.3125C13.2792 11.2375 13.5167 11.2167 13.75 11.25L17.2 11.95C17.4333 12.0167 17.625 12.1375 17.775 12.3125C17.925 12.4875 18 12.6833 18 12.9V16.95C18 17.25 17.9 17.5 17.7 17.7C17.5 17.9 17.25 18 16.95 18ZM3.025 6L4.675 4.35L4.25 2H2.025C2.10833 2.68333 2.225 3.35833 2.375 4.025C2.525 4.69167 2.74167 5.35 3.025 6ZM11.975 14.95C12.625 15.2333 13.2875 15.4583 13.9625 15.625C14.6375 15.7917 15.3167 15.9 16 15.95V13.75L13.65 13.275L11.975 14.95Z"
                            fill="black" />
                        </svg>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          @else
            <div class="text-center">
              <p class="font18 text-danger">No data found.</p>
            </div>
          @endif

        </div>
      </div>

    </div>
  </div>
</section>
