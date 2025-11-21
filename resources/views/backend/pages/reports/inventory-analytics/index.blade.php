@extends('backend.layouts.app')
@section('page-styles')
  <link rel="stylesheet" href="{{ asset('/public/backend/assetss/daterangepicker.css') }}">
@endsection

@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />
  <div class="row">
    <div class="col-xl-6 col-lg-6">
      @include('backend.includes.inventory-overview',['width' => 100])
    </div>
    <div class="col-xl-6 col-lg-6">
      @include('backend.includes.inventory-turnover-rate',['width' => 100])
    </div>
  </div>

  <x-list-card :cardHeader="$cardHeader" :baseRoute="'admin.inventory'">
    <livewire:inventory-manage.inventories-table page="inventory-analytics"/>
  </x-list-card>

@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/moment.min.js') }}"></script>
  <script src="{{ asset('/public/backend/assetss/js/daterangepicker.js') }}"></script>
@endsection
