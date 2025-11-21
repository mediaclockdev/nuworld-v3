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
    Schema::create('scratch_card_rewards', function (Blueprint $table) {
      $table->id();
      $table->enum('type', ['fixed', 'percentage', 'coupon'])->nullable(false);
      $table->decimal('value', 10, 2)->default(0.00);
      $table->string('coupon_code', 25)->nullable();
      $table->json('conditions')->nullable();
      $table->integer('validity_period')->nullable(false);
      $table->dateTime('valid_from')->nullable();
      $table->dateTime('valid_to')->nullable();
      $table->tinyInteger('status')->default('1')->comment('0 for Inactive; 1 for Active');
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
    Schema::dropIfExists('scratch_card_rewards');
  }
};
