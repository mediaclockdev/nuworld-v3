<section class="living__home-hero">
  <div class="living__home-hero--media">
    <div class="swiper swiper__1">
      <div class="swiper-wrapper eq-height">

        @if ($mainSliders->isNotEmpty())
          @foreach ($mainSliders as $slider)
            @php
              $settings = json_decode($slider->settings, true);
            @endphp

            <div class="swiper-slide">
              <figure>
                <img
                  src="{{ !empty($settings['image'])
                      ? asset(config('defaults.banner_image_path') . $settings['image'])
                      : asset('frontend/assets/img/home/homeslider1.jpg') }}"
                  alt="{{ $settings['alt_text'] ?? '' }}" title="{{ $slider->alt_text ?? '' }}" class="imageFit" />
              </figure>

              <div class="txt-wrp">
                <div class="container">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="inside"></div>

                      {{-- <div class="heading text-center">
                        <img src="{{ asset('public/frontend/assets/img/home/living-banner-txt.png') }}"
                          alt="{{ $settings['alt_text'] ?? '' }}" />
                      </div> --}}

                      <div class="txt">
                        <h4 class="font45 fw-normal mb-0 c--whitec">
                          {{-- {!! $settings['content'] ?? '' !!} --}}
                        </h4>
                        <a href="{{ $settings['hyper_link'] ?? '#' }}" class="btn btn-light px-5 py-2">
                          {{ $settings['btn_text'] ?? 'Explore' }}
                        </a>
                      </div>

                    </div>
                  </div>
                </div>
              </div>

            </div>
          @endforeach
        @else
          <!-- Static fallback slide if no slider data found -->
          <div class="swiper-slide">
            <figure>
              <img src="{{ asset('public/frontend/assets/img/home/homeslider1.jpg') }}" alt="Default Banner"
                class="imageFit" />
            </figure>

            <div class="txt-wrp">
              <div class="container">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="inside"></div>

                    {{-- <div class="heading text-center">
                      <img src="{{ asset('public/frontend/assets/img/home/living-banner-txt.png') }}"
                        alt="Static Banner Text" />
                    </div> --}}

                    <div class="txt">
                      <h4 class="font45 fw-normal mb-0 c--whitec">
                        {{-- Welcome to Our Mayuri Space – Experience Elegance --}}
                      </h4>
                      <a href="#" class="btn btn-light px-5 py-2">
                        Explore
                      </a>
                    </div>

                  </div>
                </div>
              </div>
            </div>

          </div>
        @endif

      </div>

      <!-- Navigation Buttons -->
      <div class="arrow-wrap">
        <div class="container">
          <div class="col-lg-12">
            <div class="inside">
              <div class="swiper-pagination-progressbar">
                <div class="swiper-pagination-progressbar-fill"></div>
              </div>
              <div class="others">
                <div class="swipper_button swiper__1--prev d-flex">
                  <span class="material-symbols-outlined font35 c--whitec">arrow_back_ios_new</span>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swipper_button swiper__1--next d-flex">
                  <span class="material-symbols-outlined font35 c--whitec">arrow_forward_ios</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Navigation Buttons -->

    </div>
  </div>
</section>
