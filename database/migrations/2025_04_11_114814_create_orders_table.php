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
    Schema::create('orders', function (Blueprint $table) {
      $table->id();

      // User reference
      $table->integer('user_id');
      $table->text('user_notes')->nullable();

      // Order and transaction identifiers
      $table->string('order_number')->unique();
      $table->string('transaction_id')->nullable();

      // Payment info
      $table->tinyInteger('payment_method')->nullable()->comment('0: COD, 1: Online Payment');
      $table->tinyInteger('payment_status')->default(0)->comment('0: Pending, 1: Success, 2: Failed');
      $table->text('payment_details')->nullable();
      $table->string('payment_failure_reason')->nullable();
      $table->tinyInteger('coupon_type')
        ->nullable()
        ->comment('1: Coupon, 2: Scratch Card Reward');

      // Coupon details
      $table->integer('coupon_id')->nullable();
      $table->decimal('coupon_discount', 10, 2)->default(0.00);

      // Order totals
      $table->decimal('order_total', 10, 2);
      $table->decimal('total_tax', 10, 2);
      $table->decimal('shipping_charge', 10, 2)->default(0.00);
      $table->text('other_charges')->nullable();
      $table->decimal('net_total', 10, 2); // After discounts, taxes, etc.

      // Order status
      $table->tinyInteger('order_status')
        ->default(0)
        ->comment('0: Awaiting Confirmation, 1: Confirmed, 2: Cancellation Initiated, 3: Cancelled, 4: Shipped, 5: Delivered');

      // Addresses
      $table->text('shipping_address');
      $table->text('billing_address');

      $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('deleted_by')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('orders');
  }
};
