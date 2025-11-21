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
    Schema::create('permissions', function (Blueprint $table) {
      $table->id();
      $table->string('name', 100);
      $table->string('description', 1000)->nullable();
      $table->string('slug', 255);
      $table->tinyInteger('status')->default(1)->comment('0 = Inactive, 1 = Active');
      $table->foreignId('created_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('deleted_by')->nullable()->constrained('admins');
      $table->timestamps();
      $table->softDeletes();
    });

    Schema::table('permissions', function (Blueprint $table) {
      $table->unique(['slug', 'deleted_at'], 'slug_unique');
      $table->unique(['name', 'deleted_at'], 'name_unique');
    });
  }


  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('permissions');
  }
};
