@extends('backend.layouts.app')
@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('/public/backend/assetss/daterangepicker.css') }}" rel="stylesheet" >
@endsection

@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[2] />
  <x-list-card :cardHeader="$cardHeader" :baseRoute="''" :addBtnShow="false">
      <livewire:reports.customer-orders-table :user_id="$customer->id" />
  </x-list-card>


@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>
  <script src="{{ asset('/public/backend/assetss/js/daterangepicker.js') }}"></script>
@endsection
@push('component-scripts')
  <script>
    $(document).ready(function() {
      $('#addBtn').attr('style', 'display: none !important');
      $('#refresh').attr('style', 'display: none !important');
    });
  </script>
@endpush
