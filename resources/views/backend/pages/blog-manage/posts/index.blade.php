@extends('backend.layouts.app')
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />
  <x-list-card :cardHeader="$cardHeader" :baseRoute="'admin.posts'">
      <livewire:blog-manage.post-table />
  </x-list-card>
@endsection
@push('component-scripts')
@endpush
