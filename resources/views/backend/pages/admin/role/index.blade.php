@extends('backend.layouts.app')
@section('page-styles')
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />
  <x-list-card :cardHeader="$cardHeader" :baseRoute="'admin.roles'">
    <livewire:admin.roles-table />
  </x-list-card>
@endsection
@section('page-scripts')
@endsection
