@extends('backend.layouts.app')

@section('page-styles')
  <style>
    .custom-card {
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .custom-card .card-header {
      border-bottom: 1px solid #dee2e6;
      padding: 1rem 1.5rem;
      font-weight: 600;
      font-size: 1.25rem;
    }

    .banner-thumbnail {
      max-width: 100px;
      height: 80px;
      object-fit: contain;
      border-radius: 8px;
      border: 1px solid #ddd;
      padding: 4px;
      background-color: #fff;
    }

    .fallback-banner {
      max-width: 100%;
      height: 120px;
      object-fit: contain;
      border-radius: 8px;
      border: 1px solid #ddd;
      padding: 4px;
      background-color: #fff;
    }

    .card-title {
      font-size: 1rem;
      font-weight: 500;
    }
  </style>
@endsection

@section('content')
  <x-breadcrumb :pageTitle="$cardHeader" :skipLevels=[] />

  {{-- Both Web & App Banners Card --}}
  @if (!empty($bothBanners))
    <div class="card mb-4 custom-card">
      <div class="card-header text-dark">
        <h4 class="mb-0">Both Web & App Banners</h4>
      </div>
      <div class="card-body">
        <div class="row">
          @foreach ($bothBanners as $key => $value)
            <div class="col-sm-4 mb-3">
              @include('admin.banner.partial.banner-card', ['key' => $key, 'value' => $value])
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @endif

  {{-- Web Banners Card --}}
  @if (!empty($webBanners))
    <div class="card mb-4 custom-card">
      <div class="card-header  text-dark">
        <h4 class="mb-0">Web Banners</h4>
      </div>
      <div class="card-body">
        <div class="row">
          @foreach ($webBanners as $key => $value)
            <div class="col-sm-4 mb-3">
              @include('admin.banner.partial.banner-card', ['key' => $key, 'value' => $value])
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @endif

  {{-- App Banners Card --}}
  @if (!empty($appBanners))
    <div class="card mb-4 custom-card">
      <div class="card-header  text-dark">
        <h4 class="mb-0">App Banners</h4>
      </div>
      <div class="card-body">
        <div class="row">
          @foreach ($appBanners as $key => $value)
            <div class="col-sm-4 mb-3">
              @include('admin.banner.partial.banner-card', ['key' => $key, 'value' => $value])
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @endif


@endsection

@section('page-scripts')
@endsection
