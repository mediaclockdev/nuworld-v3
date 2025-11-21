@extends('backend.layouts.app')
@section('page-styles')
  <style>
    .disabled-link {
      pointer-events: none;
      opacity: 0.5;
      cursor: not-allowed;
    }
  </style>
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />
  <x-list-card :cardHeader="$cardHeader" :baseRoute="'admin.shipment-management'">
    <livewire:order-manage.shipping-table />
  </x-list-card>
@endsection
@section('page-scripts')
@endsection
@push('component-scripts')
  <script>
    $(document).ready(function() {
      $('#addBtn').attr('style', 'display: none !important');
    });
  </script>
@endpush
