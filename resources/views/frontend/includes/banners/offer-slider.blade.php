{{-- @php
  pd($homePageOfferBanners);
@endphp --}}
{{-- <div class="furniture__top_scroller py-2">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="marquee">
          @forelse ($offerSliders as $slider)
            <a href="{{ $slider->hyper_link ?? '#' }}">{!! strip_tags($slider->content ?? '', '<a><img><strong><span><em><ul><li><ol>') !!}</a>
          @empty
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div> --}}

@if (!empty($homePageOfferBanners) && count($homePageOfferBanners) > 0)
  @php
    $offerSpeed = 30000; // Default speed

    if (!empty($homePageOfferSpeed['settings'])) {
        $speedSettings = json_decode($homePageOfferSpeed['settings'], true);
        $offerSpeed = isset($speedSettings['speed']) ? $speedSettings['speed'] : 30000;
    }
  @endphp

  <div class="furniture__top_scroller py-2">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          <div class="marquee" data-speed="{{ $offerSpeed }}">
            @foreach ($homePageOfferBanners as $banner)
              @php
                $settings = json_decode($banner['settings'] ?? '{}', true);
                $hyper_link = $settings['hyper_link'] ?? '#';
                $content = $banner['title'] ?? '';
              @endphp
              <a href="{{ $hyper_link }}">{!! strip_tags($content, '<a><img><strong><span><em><ul><li><ol>') !!}</a>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
@endif
