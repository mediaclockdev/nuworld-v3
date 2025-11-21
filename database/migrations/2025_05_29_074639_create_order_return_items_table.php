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
    Schema::create('order_return_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('order_return_id')->constrained()->cascadeOnDelete();
      $table->foreignId('order_products_id')->constrained()->cascadeOnDelete();
      $table->integer('quantity');
      $table->text('reason')->nullable();
      $table->enum('status', ['pending', 'approved', 'declined'])->default('pending');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('order_return_items');
  }
};
