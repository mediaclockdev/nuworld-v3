@extends('backend.layouts.app')
@section('page-styles')
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />
  <x-list-card :cardHeader="$cardHeader" :baseRoute="'admin.product-variations'">
    @include('backend.pages.product-manage.products.partials.variant-price-image')
  </x-list-card>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script>
    $('#addBtn').attr('style', 'display: none !important');
  </script>
@endsection
