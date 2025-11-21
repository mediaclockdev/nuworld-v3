@php
  $settings = [];
  if (!empty($categorySaleBlock) && isset($categorySaleBlock->settings)) {
      $settings = json_decode($categorySaleBlock->settings, true);
  }
@endphp
<section class="furniture__saleblock_fullw">
  <div class="container-xxl">
    <div class="row g-0">
      <div class="col-lg-5">
        <div class="infoblock flow-rootX2">
          <h3 class="fw-normal c--whitec font45">
            {{ $categorySaleBlock->title ?? 'Mayuri Made Beautiful with Functional Elegance' }}
          </h3>
          {!! $settings['content'] ?? '' !!}
          <div class="act">
            <a href="{{ $settings['hyper_link'] ?? '#' }}" class="btn btn-outline-light px-4 py-2">
               {{ $settings['btn_text'] ?? 'View Collections' }}
            </a>
          </div>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="inside">
            <figure><img
              src="{{ !empty($settings['image']) ? asset(config('defaults.banner_image_path') . $settings['image']) : asset('public/frontend/assets/img/default_thumb.jpg') }}"
              alt="{{ $settings['alt_text'] ?? '' }}" class="imageFit" /></figure>
        </div>
      </div>
    </div>
  </div>
</section>
