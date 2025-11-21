<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ImageServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register()
  {
    $this->app->singleton(\App\Services\ImageUploadService::class, function ($app) {
      return new \App\Services\ImageUploadService();
    });
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    //
  }
}
