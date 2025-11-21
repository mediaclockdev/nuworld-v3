@extends('backend.layouts.app')
@section('page-styles')
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[0] />
  <x-list-card :cardHeader="$cardHeader" :baseRoute="'admin.departments'">
    <livewire:system.department-table />
  </x-list-card>
@endsection
@section('page-scripts')
@endsection
