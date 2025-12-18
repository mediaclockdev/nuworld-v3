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
    Schema::create('product_variants', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->constrained('products')->cascadeOnUpdate()->restrictOnDelete();
      $table->string('name');
      $table->decimal('regular_price', 10, 2);
      $table->decimal('sale_price', 10, 2)->nullable();
      $table->date('sale_start_date')->nullable();
      $table->date('sale_end_date')->nullable();
      $table->string('sku', 255)->unique();
      $table->json('attribute_details')->nullable();
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
    Schema::dropIfExists('product_variants');
  }
};
