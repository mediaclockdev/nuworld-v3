@extends('backend.layouts.app')
@section('page-styles')
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />
  <x-list-card :cardHeader="$cardHeader" :baseRoute="'admin.inventory'">
    <livewire:inventory-manage.inventories-table page="inventory" />
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
