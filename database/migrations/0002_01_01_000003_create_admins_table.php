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
    Schema::create('admins', function (Blueprint $table) {
      $table->id();
      $table->string('first_name', 100);
      $table->string('middle_name', 100)->nullable();
      $table->string('last_name', 100)->nullable();
      $table->string('email', 100);
      $table->string('phone', 20);
      $table->string('password', 255);
      $table->string('avatar', 255)->nullable();
      $table->tinyInteger('status')->default(1);
      $table->integer('created_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->integer('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->integer('deleted_by')->nullable()->constrained('admins');
      $table->rememberToken();
      $table->timestamps();
      $table->softDeletes();
    });

    Schema::create('admin_password_reset', function (Blueprint $table) {
      $table->id();
      $table->foreignId('admin_id')->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->string('token');
      $table->string('ip_address', 250);
      $table->string('user_agent', 250);
      $table->tinyInteger('is_active')->default(0)->comment('0 => not used, 1 => used');
      $table->timestamps();
      $table->softDeletes();
    });

    //Create Deleted Admins Constraints for Soft Delete
    Schema::table('admins', function (Blueprint $table) {
      $table->unique(['email', 'deleted_at'], 'email_unique');
      $table->unique(['phone', 'deleted_at'], 'phone_unique');
    });
  }
  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('admins');
  }
};
