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
    Schema::create('role_permissions', function (Blueprint $table) {
      $table->foreignId('role_id')->constrained('roles')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('sub_module_id')->constrained('sub_modules')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('permission_id')->constrained('permissions')->cascadeOnUpdate()->restrictOnDelete();
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
    Schema::dropIfExists('role_permissions');
  }
};
