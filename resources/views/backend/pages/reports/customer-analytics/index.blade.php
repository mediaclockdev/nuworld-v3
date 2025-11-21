@extends('backend.layouts.app')
@section('page-styles')

@endsection

@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />
  <div class="row">
    <div class="col-xl-6 col-lg-6">
      @include('backend.includes.customer-analytics.new-vs-returning-customers',['zoomInOut' => true])
    </div>
    <div class="col-xl-6 col-lg-6">
      {{-- Customer Demographics --}}
      @include('backend.includes.deals-overview', ['width' => 100])
    </div>
  </div>

  <x-list-card :cardHeader="'Top Customer Revenue'" :baseRoute="'admin.departments'" :addBtnShow="false">
    <livewire:reports.top-customer-by-revenue-table />
  </x-list-card>


  <x-list-card :cardHeader="'Search Query'" :baseRoute="'admin.departments'" :addBtnShow="false">
    <livewire:reports.search-query-table />
  </x-list-card>
@endsection
@section('page-scripts')
@endsection
