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
    Schema::create('product_variant_images', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_variant_id')->constrained('product_variants')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('media_gallery_id')->constrained('media_galleries')->nullable()->cascadeOnUpdate()->restrictOnDelete();
      $table->tinyInteger('is_default')->default(0)->comment('0 for No; 1 for Yes');
      $table->tinyInteger('sort_order')->default(0)->index();
      $table->tinyInteger('status')->default(1);
      $table->foreignId('created_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('deleted_by')->nullable()->constrained('admins');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('product_variant_images');
  }
};
