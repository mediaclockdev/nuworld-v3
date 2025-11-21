@php
  $fullQuery = request()->query();
@endphp
<div class="row align-items-center mt-5">
  <div class="col-lg-6">
    @if ($variants->count() > 0)
      <p class="font18 m-0">Showing
        <strong>{{ $variants->firstItem() }}-{{ $variants->lastItem() }}</strong> of
        <strong>{{ $variants->total() }}</strong> items
      </p>
    @else
      No products found
    @endif
  </div>
  <div class="col-lg-6">
    <div class="buttons-inline d-flex justify-content-end align-items-center gap-3">
      <div class="filterpop">
        <button type="button" class="btn btn-dark d-flex justify-content-end align-items-center gap-2 px-3"
          data-bs-toggle="modal" data-bs-target="#sidefilter"><span
            class="material-symbols-outlined font20">filter_alt</span> Filter</button>
      </div>
      @include('frontend.includes.sort')
    </div>
  </div>
</div>
@if (request()->has('min_price') || request()->has('max_price') || count($selectedFilters) >0)
<div class="filter-Wrap">
  @if (request()->has('min_price') || request()->has('max_price'))
  {{-- Price Filter --}}
    @php
      $priceQuery = $fullQuery;
      unset($priceQuery['min_price'], $priceQuery['max_price']);
      $priceUrl = url()->current() . '?' . http_build_query($priceQuery);
    @endphp
    <div class="alert alert-secondary alert-dismissible fade show py-1 px-3 rounded-pill" role="alert">
      <strong>Price:</strong> {{ request('min_price') ?? 0 }} – {{ request('max_price') ?? '∞' }}
      <a href="{{ $priceUrl }}" class="btn-close" aria-label="Close"></a>
    </div>
  @endif

  {{-- Attribute Filters --}}
  @foreach ($selectedFilters as $key => $values)
    @foreach ($values as $value)
      @php
        $query = $fullQuery;
        if (isset($query['attributes'][$key])) {
            $query['attributes'][$key] = array_filter($query['attributes'][$key], fn($v) => $v !== $value);
            if (empty($query['attributes'][$key])) {
                unset($query['attributes'][$key]);
            }
        }
        $filterUrl = url()->current() . '?' . http_build_query($query);
      @endphp
      <div class="alert alert-secondary alert-dismissible fade show py-1 px-3 rounded-pill" role="alert">
        <strong>{{ $key }}:</strong> {{ $value }}
        <a href="{{ $filterUrl }}" class="btn-close" aria-label="Close"></a>
      </div>
    @endforeach
  @endforeach
</div>
@endif

@include('frontend.includes.filter-modal', [
    'route' => route('search.product', $product->searchSlug),
])
