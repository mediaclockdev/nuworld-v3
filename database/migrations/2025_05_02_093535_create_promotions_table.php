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
    Schema::create('promotions', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->dateTime('promotion_start_from')->nullable();
      $table->dateTime('promotion_end_to')->nullable();
      $table->text('description')->nullable();
      $table->tinyInteger('promotion_mode')->comment('1=>Product Wise,2=>Category Wise')->default(1);
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
    Schema::dropIfExists('promotions');
  }
};
