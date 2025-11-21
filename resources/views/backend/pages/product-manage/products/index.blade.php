@extends('backend.layouts.app')
@section('page-styles')
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />
  <x-list-card :cardHeader="$cardHeader" :baseRoute="'admin.products'">
    <livewire:product-manage.product-table />
  </x-list-card>
@endsection
@section('page-scripts')
@endsection
