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
    Schema::table('user_portraits', function (Blueprint $table) {

      // Rename existing image column
      $table->renameColumn('image', 'original_image');
    });

    Schema::table('user_portraits', function (Blueprint $table) {

      // New processed image
      $table->string('processed_image')->nullable()->after('original_image');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('user_portraits', function (Blueprint $table) {

      $table->dropColumn('processed_image');

      $table->renameColumn('original_image', 'image');
    });
  }
};
