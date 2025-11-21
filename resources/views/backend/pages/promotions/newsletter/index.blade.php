@extends('backend.layouts.app')

@section('page-styles')
  {{-- Add custom styles here if needed --}}
@endsection

@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="[]" />

  <x-list-card :cardHeader="$cardHeader" :baseRoute="'admin.newsletter'">
    <livewire:promotions.newsletter-table />
  </x-list-card>
@endsection

@push('component-scripts')
  <script>
    $(document).ready(function() {
      $('#addBtn').attr('style', 'display: none !important');
    });
  </script>
@endpush
