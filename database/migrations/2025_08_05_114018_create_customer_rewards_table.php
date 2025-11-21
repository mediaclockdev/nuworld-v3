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
    Schema::create('customer_rewards', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('customer_id');
      $table->unsignedBigInteger('order_id');
      $table->unsignedBigInteger('scratch_card_reward_id');
      $table->string('scratch_card_code');
      $table->tinyInteger('status')->default(1)->comment('1 => Active, 2 => Used, 3 => Expired');
      $table->dateTime('expiry_date');
      $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->timestamps();

      $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
      $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
      $table->foreign('scratch_card_reward_id')->references('id')->on('scratch_card_rewards')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('customer_rewards');
  }
};
