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
    Schema::create('user_activities', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
      $table->string('ip_address');
      $table->string('browser')->nullable();
      $table->string('location')->nullable();
      $table->string('os')->nullable();
      $table->string('device')->nullable();
      $table->tinyInteger('logged_in')->default(1)->comment('0 => Not logged in, 1 => Logged in');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('user_activities');
  }
};
