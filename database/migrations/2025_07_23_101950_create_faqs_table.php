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
    Schema::create('faqs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('faq_category_id')->constrained('faq_categories')->cascadeOnUpdate()->restrictOnDelete();
      $table->string('question');
      $table->text('answer')->nullable();
      $table->tinyInteger('status')->default('1')->comment('0 for Inactive; 1 for Active');
      $table->foreignId('created_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('deleted_by')->nullable()->constrained('admins');
      $table->softDeletes();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('faqs');
  }
};
