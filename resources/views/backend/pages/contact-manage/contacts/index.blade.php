@extends('backend.layouts.app')
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />
  <x-list-card :cardHeader="$cardHeader" :baseRoute="'admin.contacts'">
      <livewire:contact-manage.contact-table />
  </x-list-card>
@endsection
@section('page-scripts')
  <script>
    $(document).ready(function() {
      $('#addBtn').attr('style', 'display: none !important');
    });
  </script>
@endsection
