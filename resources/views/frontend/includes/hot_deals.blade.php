@php
  // Ensure hotDeals is an array with up to 4 items
  // $hotDeals = is_array($hotDeals) ? array_slice($hotDeals, 0, 4) : [];
  // pd($hotDeals);
  $defaultImages = [
      url('public/backend/assetss/images/static_images/default_1.jpg'),
      url('public/backend/assetss/images/static_images/default_2.jpg'),
      url('public/backend/assetss/images/static_images/default_3.jpg'),
      url('public/backend/assetss/images/static_images/default_4.jpg'),
  ];

@endphp

<section class="furniture__hotdeals_wrap">
  <div class="container-xxl flow-rootX3">
    <div class="row">
      <div class="col-lg-12">
        <div class="d-flex justify-content-start align-items-end gap-5">
          <h2 class="fw-normal m-0 font35 c--blackc">Hot Deals & Top Picks</h2>
          {{-- <p class="m-1 font18">Discover our most popular items and elevate your space with style.</p> --}}
          <p class="m-1 font18">Discover our most popular grocery picks and fill your kitchen with freshness and flavor.</p>
        </div>
      </div>
    </div>

    <div class="row">
      @for ($index = 0; $index < 4; $index++)
        @php
          $deal = $hotDeals[$index] ?? null;

          if ($deal) {
              $settings = json_decode($deal['settings'] ?? '{}', true);
              $image = !empty($settings['image'])
                  ? asset(config('defaults.banner_image_path') . $settings['image'])
                  : $defaultImages[$index];
              $link = $settings['hyper_link'] ?? 'javascript:void(0);';
              $alt = $settings['alt_text'] ?? 'Hot Deal';
          } else {
              $image = $defaultImages[$index];
              $link = 'javascript:void(0);';
              $alt = 'Hot Deal';
          }

          $cardClass = match ($index) {
              0 => 'one',
              1 => 'two',
              2 => 'three',
              3 => 'four',
              default => '',
          };
        @endphp

        {{-- Open container for index 1-3 --}}
        @if ($index === 1)
          <div class="col-lg-7">
            <div class="row flow-rootX">
        @endif

        {{-- Image blocks --}}
        @if ($index === 0)
          <div class="col-lg-5">
            <div class="hot_deals_card {{ $cardClass }}">
              <a href="{{ $link }}" title="Hot Deal"></a>
              <figure class="m-0 imageTrans">
                <img src="{{ $image }}" alt="{{ $alt }}" target="_blank" class="imageFit" />
              </figure>
            </div>
          </div>
        @elseif (in_array($index, [1, 2, 3]))
          <div class="col-lg-{{ $index == 1 ? '12' : '6' }}">
            <div class="hot_deals_card {{ $cardClass }}">
              <a href="{{ $link }}" title="Hot Deal"></a>
              <figure class="m-0 imageTrans">
                <img src="{{ $image }}" alt="{{ $alt }}" target="_blank" class="imageFit" />
              </figure>
            </div>
          </div>
        @endif

        {{-- Close container for index 1-3 --}}
        @if ($index === 3)
    </div>
  </div>
  @endif
  @endfor
  </div>
  </div>
</section>
