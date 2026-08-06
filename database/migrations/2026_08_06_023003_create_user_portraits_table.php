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
    Schema::create('user_portraits', function (Blueprint $table) {

      $table->id();

      $table->foreignId('user_id')
        ->nullable()
        ->constrained('users')
        ->cascadeOnUpdate()
        ->cascadeOnDelete();

      $table->enum('gender', [
        'male',
        'female',
      ]);

      $table->string('image', 255);

      $table->string('thumbnail', 255)->nullable();

      $table->integer('width')->nullable();

      $table->integer('height')->nullable();

      $table->decimal('aspect_ratio', 8, 4)->nullable();

      $table->tinyInteger('status')
        ->default(1)
        ->comment('0 = Inactive, 1 = Active');

      $table->foreignId('created_by')
        ->nullable()
        ->constrained('admins')
        ->cascadeOnUpdate()
        ->restrictOnDelete();

      $table->foreignId('updated_by')
        ->nullable()
        ->constrained('admins')
        ->cascadeOnUpdate()
        ->restrictOnDelete();

      $table->foreignId('deleted_by')
        ->nullable()
        ->constrained('admins');

      $table->timestamps();

      $table->softDeletes();
    });

    Schema::table('user_portraits', function (Blueprint $table) {

      $table->index('gender');

      $table->index('status');

      $table->index('created_at');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('user_portraits');
  }
};
