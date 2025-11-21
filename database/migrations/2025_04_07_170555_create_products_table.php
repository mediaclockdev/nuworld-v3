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
    Schema::create('products', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description')->nullable();
      $table->string('sku', 100)->unique()->index();
      $table->enum('type', ['simple', 'variable'])->default('variable');
      $table->foreignId('category_id')->constrained('product_categories')->cascadeOnUpdate()->restrictOnDelete();
      $table->tinyInteger('status')->default(1)->comment('0 for Inactive; 1 for Active');
      $table->foreignId('created_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('deleted_by')->nullable()->constrained('admins');
      $table->text('product_details')->nullable();
      $table->text('specifications')->nullable();
      $table->text('care_maintenance')->nullable();
      $table->text('warranty')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('products');
  }
};
