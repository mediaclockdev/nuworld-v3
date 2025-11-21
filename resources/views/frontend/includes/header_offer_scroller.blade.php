<div class="furniture__top_scroller py-2">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="marquee">
          @forelse ($homePageBanners as $slider)
          @if ($slider->positionValue->name == 'Offer-Slider')
            <a href="{{ $slider->hyper_link  ?: '#'}}" title="Mayuri">{!! $slider->content ?? '' !!}</a>
          @endif
          @empty
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>
