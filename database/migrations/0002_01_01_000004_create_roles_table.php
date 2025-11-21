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
    Schema::create('roles', function (Blueprint $table) {
      $table->id();
      $table->string('name', 100);
      $table->string('description', 1000)->nullable();
      $table->tinyInteger('strength')->default(1);
      $table->tinyInteger('status')->default(1)->comment('0 = Inactive, 1 = Active');
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
    Schema::dropIfExists('roles');
  }
};
