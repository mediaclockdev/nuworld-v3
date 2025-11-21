<?php

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  use SoftDeletes;
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('supports', function (Blueprint $table) {
      $table->id();
      $table->string('first_name');
      $table->string('last_name')->nullable();
      $table->string('email');
      $table->text('message')->nullable();
      $table->softDeletes();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('supports');
  }
};
