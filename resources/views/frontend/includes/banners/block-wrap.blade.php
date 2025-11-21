@php
  $settings = [];
  if (!empty($blockWrap) && isset($blockWrap->settings)) {
      $settings = json_decode($blockWrap->settings, true);
  }
@endphp

@if (!empty($settings))
  <section class="furniture__home_content_blockwrp">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="inside text-center">
            <h1 class="font45 text-center fw-normal c--blackc" data-parallax-strength-vertical="-1.5"
              data-parallax-height="-1.5">
              <span data-parallax-target>{!! $settings['content'] ?? '' !!}</span>
            </h1>
            <figure data-parallax-strength-vertical="-1.5" data-parallax-height="-1.5">
              <img
                src="{{ !empty($settings['image']) ? asset(config('defaults.banner_image_path') . $settings['image']) : asset('public/frontend/assets/img/home/sleek-furniture.png') }}"
                alt="{{ $settings['alt_text'] ?? '' }}" data-parallax-target />
            </figure>
            <div class="act" data-parallax-strength-vertical="1.5" data-parallax-height="1.5">
              <a href="{{ $settings['hyper_link'] ?? '#' }}" class="btn btn-outline-dark px-4 py-2" data-parallax-target>
                {{ $settings['btn_text'] ?? 'View All Collections' }}
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@else
  {{-- Static fallback content --}}
  <section class="furniture__home_content_blockwrp">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="inside text-center">
            <h1 class="font45 text-center fw-normal c--blackc" data-parallax-strength-vertical="-1.5"
              data-parallax-height="-1.5">
              <span data-parallax-target>Blending sleek, contemporary design with artistic forms, our collection
                enhances every space with sophistication and comfort.</span>
            </h1>
            <figure data-parallax-strength-vertical="1.5" data-parallax-height="1.5">
              <img src="{{ asset('public/frontend/assets/img/home/sleek-furniture.png') }}" alt="Mayuri"
                title="Mayuri" data-parallax-target />
            </figure>
            <div class="act" data-parallax-strength-vertical="-1.5" data-parallax-height="-1.5">
              <a href="javascript:void();" class="btn btn-outline-dark px-5 py-3" title="View All Collections"
                data-parallax-target>
                View All Collections
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endif
