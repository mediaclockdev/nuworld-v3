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
    Schema::create('addresses', function (Blueprint $table) {
      $table->id();
      $table->foreignId('admin_id')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
      $table->tinyInteger('primary')->default(1)->index()->comment('0 for No; 1 for Yes');
      $table->tinyInteger('type')->default(2)->index()->comment('0 for Shipping, 1 for Billing, 2 for Other');
      $table->string('name', 100)->nullable();
      $table->string('email', 100)->index()->nullable();
      $table->string('phone', 20)->index()->nullable();
      $table->string('country_id', 20)->default(0);
      $table->unsignedBigInteger('state_id');
      $table->string('city', 100)->nullable();
      $table->string('pin', 20)->index();
      $table->text('address_1');
      $table->text('address_2')->nullable();
      $table->string('landmark', 255)->nullable(); // Added
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
    Schema::dropIfExists('addresses');
  }
};
