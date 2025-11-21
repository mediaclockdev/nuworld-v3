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
    Schema::create('product_attribute_values', function (Blueprint $table) {
      $table->id();
      $table->foreignId('attribute_id')->constrained('product_attributes')->cascadeOnUpdate()->restrictOnDelete();
      $table->string('value', 150);
      $table->string('value_details', 255)->nullable();
      $table->integer('sequence')->default(0);
      $table->tinyInteger('status')->default(1)->index()->comment('0 for Inactive; 1 for Active');

      $table->foreignId('created_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('deleted_by')->nullable()->constrained('admins');
      $table->timestamps();
      $table->softDeletes();
    });
    // Indexes
    Schema::table('product_attribute_values', function (Blueprint $table) {
      $table->unique(['value', 'deleted_at'], 'value_unique');
      $table->index('created_at');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('product_option_values');
  }
};
