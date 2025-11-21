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
    Schema::create('value_list_masters', function (Blueprint $table) {
      $table->id();
      $table->string('name', 150);
      $table->string('code', 150)->index();
      $table->foreignId('created_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('deleted_by')->nullable()->constrained('admins');
      $table->tinyInteger('status')->default(1)->index()->comment('0 for Inactive; 1 for Active');
      $table->timestamps();
      $table->softDeletes();
    });

    // Indexes
    Schema::table('value_list_masters', function (Blueprint $table) {
      $table->unique(['code', 'deleted_at'], 'code_unique');
      $table->index('created_at');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('value_list_masters');
  }
};
