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
    Schema::create('carts', function (Blueprint $table) {
      $table->id();
      $table->integer('user_id')->nullable();
      $table->integer('product_variant_id');
      $table->integer('quantity')->default(1);
      $table->tinyInteger('status')->default(0)->comment('0 = In Cart, 1 = Checked Out, 2 = Removed by User');
      $table->boolean('is_saved_for_later')->default(false);

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
    Schema::dropIfExists('carts');
  }
};
