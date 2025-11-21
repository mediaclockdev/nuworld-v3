<?php

use App\Http\Controllers\SupportController;
use App\Http\Middleware\isSuperAdmin;
use Illuminate\Support\Facades\Route;


Route::view('/', 'welcome');

Route::group(['middleware' => [isSuperAdmin::class], 'prefix' => 'support'], function () {
  Route::controller(SupportController::class)->group(function () {
    Route::get('/phpinfo', 'phpinfo');
    Route::get('/{key}/db-migrate-list', 'migrateList');
    // Route::get('/{key}/db-migrate', 'migrate');
    // Route::get('/{key}/db-migrate-specific/{migrationName}', 'migrateByName');
    // Route::get('/{key}/db-seed', 'seed');
    // Route::get('/{key}/db-seedby/{seedName}', 'seedBy');
    Route::get('/{key}/optimize-clear', 'optimizeClear');
    Route::get('/create-symlink', 'createSymLink');
    Route::get('/{key}/token-generate', 'generateToken');
    Route::get('/{key}/table/{tblName}', 'showTable');
  });
});


require __DIR__ . '/backend.php';
require __DIR__ . '/frontend.php';
require __DIR__ . '/livewire.php';


// Fallback Route (Must be last)
Route::fallback(function () {
  if (request()->is('admin') || request()->is('admin/*')) {
    return response()->view('errors.404', ['pageTitle' => '404'], 404);
  }

  return response()->view('frontend.errors.404', [], 404);
});
