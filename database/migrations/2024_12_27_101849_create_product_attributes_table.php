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
    Schema::create('product_attributes', function (Blueprint $table) {
      $table->id();
      $table->string('name', 150);
      $table->integer('sequence')->default(0);
      $table->tinyInteger('status')->default(1)->index()->comment('0 for Inactive; 1 for Active');
      $table->foreignId('created_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('deleted_by')->nullable()->constrained('admins');
      $table->timestamps();
      $table->softDeletes();
    });
    // Indexes
    Schema::table('product_attributes', function (Blueprint $table) {
      $table->unique(['name', 'deleted_at'], 'name_unique');
      $table->index('created_at');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('product_attributes');
  }
};
