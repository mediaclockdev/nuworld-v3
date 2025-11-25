<?php

use Livewire\Livewire;
use Illuminate\Support\Facades\Route;

// Read BASE_PATH directly from .env
$prefix = env('BASE_PATH', '');

// Normalize the path (remove double slashes)
$prefix = trim($prefix, '/');
$prefix = $prefix ? '/' . $prefix : '';

Livewire::setScriptRoute(function ($handle) use ($prefix) {
  return Route::get($prefix . '/livewire/livewire.js', $handle);
});

Livewire::setUpdateRoute(function ($handle) use ($prefix) {
  return Route::post($prefix . '/livewire/update', $handle);
});
