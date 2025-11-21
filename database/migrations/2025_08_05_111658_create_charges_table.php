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
    Schema::create('charges', function (Blueprint $table) {
      $table->id();

      $table->string('name'); // e.g., Standard Delivery, Handling Fee
      $table->enum('calculation_method', ['fixed', 'percentage', 'weight_based', 'distance_based'])->default('fixed');
      $table->decimal('value', 10, 2)->default(0);

      $table->boolean('is_mandatory')->default(true);
      $table->text('conditions')->nullable(); // JSON or free-text based rules (e.g., {"min_order": 500, "region": "IN"})
      $table->string('applicability')->nullable(); // e.g., "all_orders", "above_100", "zone_north"

      $table->boolean('status')->default(true); // status of the charge
      $table->foreignId('created_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('deleted_by')->nullable()->constrained('admins');
      $table->timestamps();
      $table->softDeletes(); // for safe deletion
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('charges');
  }
};
