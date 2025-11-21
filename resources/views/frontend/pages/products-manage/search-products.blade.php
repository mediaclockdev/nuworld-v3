@extends('frontend.layouts.app')
@php
  $fullQuery = request()->query();
  // pd($priceRange);
@endphp
@section('title', @$title)

@section('content')
  {{-- @include('frontend.includes.listing-banner') --}}
  <section class="category_hero fullBG mt-0 p-0">
    <div class="container-fluid p-0">
      <div class="row">
        <div class="col-lg-12">
          <figure class="m-0"><img src="{{ asset('public/frontend/assets/img/home/search_banner.jpg') }}"
              alt="" />
          </figure>
        </div>
      </div>
    </div>
  </section>
  <section class="furniture__product_listingwrp">
    <div class="container-xxl flow-rootX2">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="font45 fw-normal c--blackc">Collections</h1>
        </div>
      </div>
      @include('frontend.includes.list-top-slider', ['fromPage' => 'category'])
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
          @if ($variants->count() > 0)
            <div class="buttons-inline d-flex justify-content-end align-items-center gap-3">
              <div class="filterpop">
                <button type="button" class="btn btn-dark d-flex justify-content-end align-items-center gap-2 px-3"
                  data-bs-toggle="modal" data-bs-target="#sidefilter"><span
                    class="material-symbols-outlined font20">filter_alt</span> Filter</button>
              </div>

              @include('frontend.includes.sort')
            </div>
          @endif
        </div>
      </div>
      <div class="filter-Wrap mt-2">
        {{-- Price Filter --}}
        @if (request()->has('min_price') || request()->has('max_price'))
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
      <div class="row">
        <div class="col-lg-12">
          <div class="furniture--grid-4 y-axis mt-3">
            @include('frontend.includes.product-card', ['products' => $variants])
          </div>
        </div>
        <div class="mt-4">
          {{ $variants->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
      </div>


    </div>
  </section>
  @if ($variants->count() > 0)
    @include('frontend.includes.filter-modal', [
        'route' => route(
            'base.search',
            request()->except(['min_price', 'max_price', 'page', 'sort', 'attributes'])),
    ])
  @endif

@endsection
