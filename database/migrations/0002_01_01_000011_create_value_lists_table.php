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
    Schema::create('value_lists', function (Blueprint $table) {
      // Columns
      $table->id();
      $table->string('name', 100);
      $table->foreignId('master_id')->constrained('value_list_masters')->cascadeOnUpdate()->restrictOnDelete();
      $table->string('icon', 250)->nullable();
      $table->string('value_1', 250)->nullable();
      $table->string('value_2', 250)->nullable();
      $table->tinyInteger('status')->default(1)->index()->comment('0 for Inactive; 1 for Active');
      $table->foreignId('created_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('deleted_by')->nullable()->constrained('admins');
      $table->timestamps();
      $table->softDeletes();
    });

    // Indexes
    Schema::table('value_lists', function (Blueprint $table) {
      $table->unique(['name', 'deleted_at'], 'name_unique');
      $table->index('created_at');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('value_lists');
  }
};
