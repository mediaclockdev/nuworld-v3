<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('product_categories', function (Blueprint $table) {

      $table->enum('default_tryon_region', [
        'upper_body',
        'lower_body',
        'dresses',
        'feet',
        'waist',
        'head',
        'carried',
        'face',
        'wrist',
        'none',
      ])
        ->default('none')
        ->after('category_image')
        ->comment('Default try-on region for products in this category');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('product_categories', function (Blueprint $table) {

      $table->dropColumn('default_tryon_region');
    });
  }
};
