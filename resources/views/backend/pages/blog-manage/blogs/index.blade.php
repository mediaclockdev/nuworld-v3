@extends('backend.layouts.app')
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />
  <x-list-card :cardHeader="$cardHeader" :baseRoute="'admin.blogs'">
      <livewire:blog-manage.blog-table />
  </x-list-card>
@endsection
@push('component-scripts')
@endpush
