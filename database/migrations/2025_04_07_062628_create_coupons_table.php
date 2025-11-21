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
    Schema::create('coupons', function (Blueprint $table) {
      $table->id();
      $table->string('code')->unique();
      $table->enum('type', ['Flat', 'Percentage']);
      $table->decimal('discount_amount', 8, 2);
      $table->decimal('max_discount', 8, 2)->nullable();
      $table->decimal('min_order_value', 8, 2)->default(0);
      $table->integer('max_uses')->nullable();
      $table->integer('per_user_limit')->default(1);
      $table->boolean('is_active')->default(true);
      $table->dateTime('valid_from')->nullable();
      $table->dateTime('valid_to')->nullable();

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
    Schema::dropIfExists('coupons');
  }
};
