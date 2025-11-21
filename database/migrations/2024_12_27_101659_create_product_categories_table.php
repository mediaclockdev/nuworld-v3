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
    Schema::create('product_categories', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('parent_id')->default(0);
      $table->string('title', 255);
      $table->string('slug', 255);
      $table->string('category_image', 255)->nullable();
      $table->float('tax')->default(0);
      $table->string('meta_title', 250)->nullable();
      $table->string('meta_desc', 250)->nullable();
      $table->string('meta_keywords', 250)->nullable();
      $table->string('icon', 255)->nullable();
      $table->integer('sequence');
      $table->tinyInteger('status')->default(1)->index()->comment('0 for Inactive; 1 for Active');

      //common columns
      $table->foreignId('created_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('deleted_by')->nullable()->constrained('admins');
      $table->timestamps();
      $table->softDeletes();
    });

    // Indexes
    Schema::table('product_categories', function (Blueprint $table) {
      $table->unique(['title', 'deleted_at'], 'title_unique');
      $table->unique(['slug', 'deleted_at'], 'slug_unique');
      $table->index('created_at');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('product_categories');
  }
};
