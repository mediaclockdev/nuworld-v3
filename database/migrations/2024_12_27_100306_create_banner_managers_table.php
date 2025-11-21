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
    Schema::create('banners', function (Blueprint $table) {
      $table->id();
      $table->string('title', 255);
      $table->string('hyper_link', 255)->nullable();
      $table->string('image', 255);
      $table->string('alt_text', 255)->nullable();
      $table->integer('sequence');
      $table->text('content')->nullable();
      $table->foreignId('position')->constrained('value_lists')->cascadeOnUpdate()->restrictOnDelete();
      $table->string('extra_value', 500)->nullable();

      $table->foreignId('created_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('deleted_by')->nullable()->constrained('admins');

      $table->tinyInteger('status')->default(1)->index()->comment('0 for Inactive; 1 for Active');
      $table->timestamps();
      $table->softDeletes();
    });

    // Indexes
    Schema::table('banners', function (Blueprint $table) {
      $table->unique(['title', 'deleted_at'], 'title_unique');
      $table->index('created_at');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('banner_managers');
  }
};
