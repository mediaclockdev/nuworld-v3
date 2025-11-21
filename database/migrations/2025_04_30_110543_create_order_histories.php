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
    Schema::create('order_histories', function (Blueprint $table) {
      $table->id();

      $table->integer('order_id');
      $table->date('scheduled_date')->nullable(); // unique_id for tracking items
      $table->time('scheduled_time')->nullable();
      $table->text('description')->nullable();
      $table->tinyInteger('status')
        ->default(0)
        ->comment('0: Awaiting Confirmation, 1: Confirmed, 2: Cancellation Initiated, 3: Cancelled, 4: Shipped, 5: Delivered');
      $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('updated_by')->nullable()->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('order_histories');
  }
};
