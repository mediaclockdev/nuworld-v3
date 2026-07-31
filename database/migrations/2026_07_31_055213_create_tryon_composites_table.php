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
    Schema::create('tryon_composites', function (Blueprint $table) {

      $table->string('cache_key', 64)->primary();

      $table->foreignId('tryon_avatar_id')
        ->constrained('tryon_avatars')
        ->cascadeOnUpdate()
        ->cascadeOnDelete();

      $table->text('visual_keys')
        ->comment('Sorted visual keys, comma separated');

      $table->integer('prompt_version')
        ->default(1);

      $table->string('image', 255)
        ->nullable()
        ->comment('Relative image path. Example: tryon/output/demo123.png');

      $table->enum('qa_status', [
        'pending',
        'approved',
        'rejected',
      ])->default('pending');

      $table->integer('attempts')
        ->default(0);

      $table->bigInteger('seed')
        ->nullable();

      $table->decimal('cost_usd', 8, 4)
        ->nullable();

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

    Schema::table('tryon_composites', function (Blueprint $table) {

      $table->index('qa_status');

      $table->index('created_at');

      $table->index('prompt_version');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tryon_composites');
  }
};
