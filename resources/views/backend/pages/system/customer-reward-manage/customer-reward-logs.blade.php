@extends('backend.layouts.app')
@section('content')
    <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />
    <x-list-card :cardHeader="$cardHeader" :baseRoute="'admin.customer-rewards'">
        <livewire:system.customer-reward-log-table :userId="$userId" />
    </x-list-card>
@endsection
@section('page-scripts')
  <script>
    $(document).ready(function() {
      $('#addBtn').attr('style', 'display: none !important');
    });
  </script>
@endsection
