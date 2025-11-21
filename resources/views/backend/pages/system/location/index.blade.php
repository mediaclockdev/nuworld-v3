@extends('backend.layouts.app')
@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[0] />
  <x-list-card :cardHeader="$cardHeader" :baseRoute="'admin.states'">
    <livewire:system.location-table />
  </x-list-card>
@endsection

@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>
@endsection
