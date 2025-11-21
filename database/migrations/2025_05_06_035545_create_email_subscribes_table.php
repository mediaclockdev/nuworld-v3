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
    Schema::create('email_subscribes', function (Blueprint $table) {
      $table->id();
      $table->string('email', 100)->unique();
      $table->integer('is_subscribe')->default(1)->comment('0 = Pending, 1 = Subscribe');
      $table->foreignId('created_by')->nullable();
      $table->foreignId('updated_by')->nullable();
      $table->rememberToken();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('email_subscribes');
  }
};
