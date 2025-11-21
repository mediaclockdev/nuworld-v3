@extends('backend.layouts.app')
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />
  <x-list-card :cardHeader="$cardHeader" :baseRoute="'admin.stores'">
      <livewire:store-manage.store-table />
  </x-list-card>
@endsection
@push('component-scripts')
@endpush
