@php
  $settings = [];
  if (!empty($keepFlowing) && isset($keepFlowing->settings)) {
      $settings = json_decode($keepFlowing->settings, true);
  }
  //$settings = json_decode($keepFlowing->settings, true);
@endphp

<section class="furniture__keepfrowing">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="insidewrp">
          <div class="figwrp" data-parallax-strength-vertical="-1.5" data-parallax-height="-1.5">
            <figure data-parallax-target><img
                src="{{ !empty($settings['image']) ? asset(config('defaults.banner_image_path') . $settings['image']) : asset('public/frontend/assets/img/home/furniture__keepfrowing.jpg') }}"
                alt="{{ $settings['alt_text'] ?? '' }}" class="imageFit" /></figure>
          </div>
          <div class="contentpart">
            {!! $settings['content'] ?? '' !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
