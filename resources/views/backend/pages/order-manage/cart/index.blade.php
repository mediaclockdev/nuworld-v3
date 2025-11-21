@extends('backend.layouts.app')
@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />
  <x-list-card :cardHeader="$cardHeader" :baseRoute="'admin.wishlist'">
    <livewire:order-manage.cart-table />
  </x-list-card>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>
@endsection
@push('component-scripts')
  <script>
    $(document).ready(function() {
      $('#addBtn').attr('style', 'display: none !important');

    });
  </script>
@endpush
