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
    Schema::create('tryon_sessions', function (Blueprint $table) {

      $table->id();

      $table->foreignId('user_portrait_id')
        ->constrained('user_portraits')
        ->cascadeOnUpdate()
        ->cascadeOnDelete();

      $table->foreignId('product_variant_id')
        ->constrained('product_variants')
        ->cascadeOnUpdate()
        ->cascadeOnDelete();

      $table->string('preview_image', 255)->nullable();

      $table->enum('status', [
        'pending',
        'processing',
        'completed',
        'failed',
      ])->default('pending');

      $table->text('error_message')->nullable();

      $table->timestamps();

      $table->softDeletes();

      $table->index([
        'user_portrait_id',
        'product_variant_id'
      ], 'portrait_variant_idx');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tryon_sessions');
  }
};
