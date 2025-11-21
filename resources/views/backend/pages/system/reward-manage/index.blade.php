@extends('backend.layouts.app')
@section('content')
    <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />
    <x-list-card :cardHeader="$cardHeader" :baseRoute="'admin.scratch-card-rewards'">
        <livewire:system.scratch-card-reward-table />
    </x-list-card>
@endsection
@push('component-scripts')
@endpush
