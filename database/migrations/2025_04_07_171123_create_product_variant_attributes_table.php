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
    Schema::create('product_variant_attributes', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_variant_id')->index()->constrained('product_variants')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('attribute_id')->index()->constrained('product_attributes')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('attribute_value_id')->index()->constrained('product_attribute_values')->cascadeOnUpdate()->restrictOnDelete();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('product_variant_attributes');
  }
};
