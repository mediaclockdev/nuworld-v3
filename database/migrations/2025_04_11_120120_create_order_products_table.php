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
    Schema::create('order_products', function (Blueprint $table) {
      $table->id();

      $table->integer('order_id');
      $table->string('order_item_uid')->unique(); // unique_id for tracking items
      $table->integer('variant_id');
      $table->string('sku')->nullable();
      $table->unsignedInteger('quantity')->default(1);

      $table->integer('promotion_id')->nullable(); // nullable if not applied

      $table->decimal('regular_price', 10, 2); // price before discount
      $table->decimal('sell_price', 10, 2);    // price after discount
      $table->decimal('tax_amount', 10, 2)->default(0.00);

      $table->tinyInteger('status')->default(0)->comment('0: active, 1: returned, 2: cancelled');

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
    Schema::dropIfExists('order_products');
  }
};
