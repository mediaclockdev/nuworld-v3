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
    Schema::create('payment_settings', function (Blueprint $table) {
      $table->id();
      $table->string('gateway_name'); // stripe, paypal, razorpay
      $table->string('gateway_key')->nullable(); //
      $table->string('gateway_secret')->nullable();
      $table->string('gateway_mode')->nullable();
      $table->string('gateway_other')->nullable();
      $table->tinyInteger('default_gateway')->default(0);
      $table->string('logo')->nullable();
      $table->tinyInteger('status')->default(1);
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
    Schema::dropIfExists('payment_settings');
  }
};
