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
    Schema::create('menus', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('slug')->nullable();
      $table->timestamps();
    });

    Schema::create('menu_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('menu_id')->constrained()->onDelete('cascade');
      $table->string('title');
      $table->string('slug')->nullable();
      $table->unsignedBigInteger('parent_id')->nullable();
      $table->integer('sequence')->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('menus');
    Schema::dropIfExists('menu_items');
  }
};
