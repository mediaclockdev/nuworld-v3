@extends('backend.layouts.app')
@section('page-styles')
  <link rel="stylesheet" href="{{ asset('/public/backend/assetss/daterangepicker.css') }}">
@endsection

@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />
  <div class="row">
    <div class="col-xl-6 col-lg-6">
      @include('backend.includes.sale-status')
    </div>
    <div class="col-xl-6 col-lg-6">
      @include('backend.includes.top-selling-product')
    </div>
  </div>
  <x-list-card :cardHeader="'Top Selling Products'" :baseRoute="'admin.departments'" :addBtnShow="false">
    <livewire:reports.top-selling-products-table />
  </x-list-card>
  </div>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/moment.min.js') }}"></script>
  <script src="{{ asset('/public/backend/assetss/js/daterangepicker.js') }}"></script>
@endsection
