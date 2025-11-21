@if (!empty($brandSliders) && count($brandSliders) > 0)
  <section class="furniture_brands_wrap">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">

          <div class="swiperwrp">
            <div class="swiper logoSmoothSlider">
              <div class="swiper-wrapper eq-height">
                @forelse ($brandSliders as $brandSlider)
                  @php
                    $settings = json_decode($brandSlider->settings, true);
                  @endphp
                  <div class="swiper-slide">
                    <img
                      src="{{ !empty($settings['image']) ? asset(config('defaults.banner_image_path') . $settings['image']) : asset('public/frontend/assets/img/brands/brandlogo1.jpg') }}"
                      alt="{{ $settings['alt_text'] ?? 'Furniture' }}" />
                  </div>
                @empty
                @endforelse
              </div>
            </div>
          </div>



          {{-- <div class="slider">
            <div class="slide-track">
              @forelse ($brandSliders as $brandSlider)
                @php
                  $settings = json_decode($brandSlider->settings, true);
                @endphp
                <div class="slide">
                  <img
                    src="{{ !empty($settings['image']) ? asset(config('defaults.banner_image_path') . $settings['image']) : asset('public/frontend/assets/img/brands/brandlogo1.jpg') }}"
                    alt="{{ $settings['alt_text'] ?? 'Furniture' }}" />
                </div>
              @empty
              @endforelse
            </div>
          </div> --}}

        </div>
      </div>
    </div>
  </section>
@else
  {{-- Static fallback design --}}
  <section class="furniture_brands_wrap" style="display:none">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          <div class="slider">
            <div class="slide-track">
              @for ($i = 1; $i <= 3; $i++)
                <div class="slide"><img src="{{ asset('public/frontend/assets/img/brands/brandlogo1.jpg') }}"
                    alt="Furniture" /></div>
                <div class="slide"><img src="{{ asset('public/frontend/assets/img/brands/brandlogo2.jpg') }}"
                    alt="Furniture" /></div>
                <div class="slide"><img src="{{ asset('public/frontend/assets/img/brands/brandlogo3.jpg') }}"
                    alt="Furniture" /></div>
                <div class="slide"><img src="{{ asset('public/frontend/assets/img/brands/brandlogo4.jpg') }}"
                    alt="Furniture" /></div>
                <div class="slide"><img src="{{ asset('public/frontend/assets/img/brands/brandlogo5.jpg') }}"
                    alt="Furniture" /></div>
                <div class="slide"><img src="{{ asset('public/frontend/assets/img/brands/brandlogo6.jpg') }}"
                    alt="Furniture" /></div>
                <div class="slide"><img src="{{ asset('public/frontend/assets/img/brands/brandlogo7.jpg') }}"
                    alt="Furniture" /></div>
              @endfor
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endif
