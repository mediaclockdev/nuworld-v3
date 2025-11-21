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
    Schema::create('custom_banners', function (Blueprint $table) {
      $table->id();
      $table->string('title', 255);
      $table->string('sub_title', 255);
      $table->string('position', 255)->nullable();
      $table->json('settings')->nullable();
      $table->foreignId('created_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('deleted_by')->nullable()->constrained('admins');
      $table->integer('custom_order')->nullable();
      $table->tinyInteger('status')->default(1)->index()->comment('0 for Inactive; 1 for Active');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('custom_banners');
  }
};
