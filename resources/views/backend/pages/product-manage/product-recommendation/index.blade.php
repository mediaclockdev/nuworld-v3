@extends('backend.layouts.app')

@section('page-styles')
  {{-- Add custom styles here if needed --}}
@endsection

@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="[]" />

  <x-list-card :cardHeader="$cardHeader" :baseRoute="'admin.best-sellers'">
    <livewire:product-manage.best-seller-table />
  </x-list-card>
@endsection

@section('page-scripts')
  {{-- Add page-specific scripts here if needed --}}
@endsection
