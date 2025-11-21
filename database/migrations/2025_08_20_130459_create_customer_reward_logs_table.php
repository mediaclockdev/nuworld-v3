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
    Schema::create('customer_reward_logs', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('customer_id');
      $table->unsignedBigInteger('order_id');
      $table->unsignedBigInteger('customer_reward_id');
      $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
      $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
      $table->foreign('customer_reward_id')->references('id')->on('customer_rewards')->onDelete('cascade');

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('customer_reward_logs');
  }
};
