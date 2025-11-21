<div class="modal genericmodal right fade" id="sidefilter" tabindex="-1" aria-labelledby="sidefilterLabel"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="GET" action="{{ $route }}" id="filter-form">
        <div class="modal-header">
          <h4 class="font20 fw-medium m-0" id="myModalLabel2">Filters</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="filter_ins_wrp">
            <div class="accordion accordion-flush" id="sidefilteraccord">
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingOne">
                  <button class="accordion-button ps-0 pe-0 text-uppercase font16 collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false"
                    aria-controls="flush-collapseOne">Price Range</button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                  data-bs-parent="#sidefilteraccord">
                  <div class="accordion-body">
                    <div class="price_range">
                      <div class="price-input">
                        <div class="field">
                          <span>Min</span>
                          <input type="number" class="input-min" name="min_price"
                            value="{{ request('min_price', round($priceRange['minPrice'])) }}"
                            placeholder="{{ round($priceRange['actualMinPrice']) }}"
                            data-default="{{ round($priceRange['minPrice']) }}" readonly>
                        </div>
                        <div class="separator">-</div>
                        <div class="field">
                          <span>Max</span>
                          <input type="number" class="input-max" name="max_price"
                            value="{{ request('max_price', round($priceRange['maxPrice'])) }}"
                            placeholder="{{ round($priceRange['actualMaxPrice']) }}"
                            data-default="{{ round($priceRange['maxPrice']) }}" readonly>
                        </div>
                      </div>
                      <div class="sliders">
                        <div class="progress"></div>
                      </div>
                      <div class="range-input">
                        <input type="range" class="range-min" min="{{ round($priceRange['actualMinPrice']) }}"
                          max="{{ round($priceRange['actualMaxPrice']) }}"
                          value="{{ request('min_price', round($priceRange['minPrice'])) }}">
                        <input type="range" class="range-max" min="{{ round($priceRange['actualMinPrice']) }}"
                          max="{{ round($priceRange['actualMaxPrice']) }}"
                          value="{{ round($priceRange['maxPrice']) }}">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @foreach ($attributes as $attribute)
                <div class="accordion-item">
                  <h2 class="accordion-header" id="flush-heading-{{ $attribute->id }}">
                    <button class="accordion-button ps-0 pe-0 text-uppercase font16 collapsed" type="button"
                      data-bs-toggle="collapse" data-bs-target="#flush-collapse-{{ $attribute->id }}"
                      aria-expanded="false" aria-controls="flush-collapse-{{ $attribute->id }}">
                      {{ $attribute->name }}
                    </button>
                  </h2>
                  <div id="flush-collapse-{{ $attribute->id }}" class="accordion-collapse collapse"
                    aria-labelledby="flush-heading-{{ $attribute->id }}" data-bs-parent="#sidefilteraccord">
                    <div class="accordion-body">
                      @foreach ($attribute->values as $value)
                        <label class="d-block">
                          <input type="checkbox" name="attributes[{{ $attribute->name }}][]"
                            value="{{ $value->value }}"
                            {{ isset($selectedFilters[$attribute->name]) && in_array($value->value, (array) $selectedFilters[$attribute->name]) ? 'checked' : '' }}>
                          {{ $value->value }}
                        </label>
                      @endforeach
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
          <div class="filter_action d-flex justify-content-end align-items-center gap-3">
            <a class="btn btn-outline-dark w-50 py-3" href="javascript:void();" id="clearAll" title="Clear All">Clear
              All</a>
            <button type="submit" class="btn btn-dark w-50 py-3" title="Apply">Apply</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
  <script>
    $(() => {
      const $range = $('.range-input input');
      const $progress = $('.sliders .progress');
      const $inputs = $('.price-input input');
      const minLimit = +$range[0].min,
        maxLimit = +$range[0].max;
      const gap = Math.max(10, (maxLimit - minLimit) * 0.01);
      let priceChanged = false;

      const updateProgress = (min, max) => {
        $progress.css({
          left: `${(min - minLimit) / (maxLimit - minLimit) * 100}%`,
          right: `${100 - (max - minLimit) / (maxLimit - minLimit) * 100}%`
        });
      };

      const syncValues = (min, max) => {
        min = Math.max(minLimit, Math.min(min, maxLimit));
        max = Math.max(minLimit, Math.min(max, maxLimit));
        if (max - min < gap) min = max - gap;
        $inputs.eq(0).val(min);
        $inputs.eq(1).val(max);
        $range.eq(0).val(min);
        $range.eq(1).val(max);
        updateProgress(min, max);
      };

      const updateURLWithFilters = (form) => {
        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);
        params.delete('page');

        const searchInput = form.find('input[name="q"]').val();
        searchInput ? params.set('q', searchInput) : params.delete('q');

        if (priceChanged) {
          const min = $inputs.eq(0).val();
          const max = $inputs.eq(1).val();
          params.set('min_price', min);
          params.set('max_price', max);
        } else {
          params.delete('min_price');
          params.delete('max_price');
        }

        for (const key of [...params.keys()]) {
          if (key.startsWith('attributes[')) params.delete(key);
        }

        form.find('input[type="checkbox"]:checked').each(function() {
          const name = $(this).attr('name'),
            value = $(this).val();
          if (name && value) params.append(name, value);
        });

        const action = form.attr('action') || window.location.pathname;
        const connector = action.includes('?') ? '&' : '?';
        window.location.href = `${action}${connector}${params.toString()}`;
      };


      $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        updateURLWithFilters($(this));
      });

      $inputs.on('input', function() {
        priceChanged = true;
        let min = parseInt($inputs.eq(0).val() || $inputs.eq(0).attr('placeholder'));
        let max = parseInt($inputs.eq(1).val() || $inputs.eq(1).attr('placeholder'));
        syncValues(min, max);
      });

      $range.on('input', function() {
        priceChanged = true;
        let min = +$range.eq(0).val(),
          max = +$range.eq(1).val();
        syncValues(min, max);
      });

      $('#filter-form').on('reset', () => {
        priceChanged = false;
        $inputs.val('');
        $range.eq(0).val(minLimit);
        $range.eq(1).val(maxLimit);
        updateProgress(minLimit, maxLimit);
      });

      function clearAllFilters(except = ['q']) {
        const url = new URL(location.href);
        const params = url.searchParams;
        [...params.keys()].forEach(k => {
          if (!except.includes(k)) params.delete(k);
        });
        url.search = params.toString();
        location.href = url.toString();
      }

      $('#clearAll').on('click', () => clearAllFilters());

      syncValues(+($range[0].value), +($range[1].value));
    });
  </script>
@endpush
