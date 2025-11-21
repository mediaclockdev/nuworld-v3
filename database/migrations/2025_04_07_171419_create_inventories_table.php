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
    Schema::create('inventories', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->index()->constrained('products')->onDelete('cascade');
      $table->foreignId('product_variant_id')->index()->constrained('product_variants')->onDelete('cascade');
      $table->unsignedInteger('quantity')->default(0);
      $table->unsignedInteger('max_selling_quantity')->default(0);
      $table->unsignedInteger('threshold')->default(5);
      $table->boolean('alert_sent')->default(false);
      $table->foreignId('alert_role_id')->nullable()->constrained('roles')->onDelete('restrict');
      $table->foreignId('created_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('inventories');
  }
};
