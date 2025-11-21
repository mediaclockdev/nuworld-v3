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
    Schema::create('promotion_details', function (Blueprint $table) {
      $table->id();
      $table->foreignId('promotion_id')->nullable();
      $table->foreignId('product_id')->nullable();
      $table->foreignId('product_variant_id')->nullable();
      $table->foreignId('category_id')->nullable();
      $table->foreignId('sub_category_id')->nullable();
      $table->enum('type', ['Flat', 'Percentage']);
      $table->decimal('discount_amount', 8, 2);
      $table->dateTime('valid_from')->nullable();
      $table->dateTime('valid_to')->nullable();
      $table->tinyInteger('status')->default(1)->comment('0 for Inactive; 1 for Active');
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
    Schema::dropIfExists('promotion_details');
  }
};
