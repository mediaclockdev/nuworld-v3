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
    Schema::create('tryon_avatars', function (Blueprint $table) {

      $table->id();

      $table->string('name', 150);

      $table->string('image', 255)
        ->comment('Relative image path. Example: tryon/avatars/aria.png');

      $table->boolean('is_default')
        ->default(false)
        ->comment('Default avatar for the application');

      $table->json('supported_regions')
        ->nullable()
        ->comment('JSON array of supported regions');

      $table->tinyInteger('status')
        ->default(1)
        ->comment('0 = Inactive, 1 = Active');

      // Common columns
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

    Schema::table('tryon_avatars', function (Blueprint $table) {

      $table->unique(['name', 'deleted_at'], 'tryon_avatar_name_unique');

      $table->index('status');

      $table->index('created_at');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tryon_avatars');
  }
};
