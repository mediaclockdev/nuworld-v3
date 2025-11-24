<?php

use Livewire\Livewire;
use Illuminate\Support\Facades\Route;

Livewire::setScriptRoute(function ($handle) {
  return Route::get('/maroon-crane-v2/livewire/livewire.js', $handle);
});

Livewire::setUpdateRoute(function ($handle) {
  return Route::post('/maroon-crane-v2/livewire/update', $handle);
});
