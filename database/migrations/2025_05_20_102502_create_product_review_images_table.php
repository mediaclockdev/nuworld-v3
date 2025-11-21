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
    Schema::create('product_review_images', function (Blueprint $table) {
      $table->id();
      $table->string('image', 255)->nullable();
      $table->integer('review_id')->nullable();
      // $table->integer('variant_id');
      // $table->integer('rating')->default(0);
      // $table->tinyInteger('status')->default(1)->index()->comment('0 for Inactive; 1 for Active');
      $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('updated_by')->nullable()->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('deleted_by')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('product_review_images');
  }
};
