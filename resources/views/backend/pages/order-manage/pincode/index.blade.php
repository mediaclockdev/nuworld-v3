@extends('backend.layouts.app')
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />
  <x-list-card :cardHeader="$cardHeader" :baseRoute="'admin.pincode'">
      <livewire:order-manage.pincode-table />
  </x-list-card>
@endsection
@push('component-scripts')
  {{-- <script>
    $(document).ready(function() {
      $('#addBtn').attr('style', 'display: none !important');
      $('#refresh').attr('style', 'display: none !important');
    });
  </script> --}}
@endpush
