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
    Schema::table('product_variants', function (Blueprint $table) {

      $table->boolean('tryon_enabled')
        ->default(false)
        ->after('status')
        ->comment('Enable or disable try-on for this variant');

      $table->enum('tryon_region', [
        'upper_body',
        'lower_body',
        'dresses',
        'feet',
        'waist',
        'head',
        'carried',
        'face',
        'wrist',
        'none',
      ])
        ->default('none')
        ->after('tryon_enabled')
        ->comment('Region where the product is worn');

      $table->string('visual_key', 255)
        ->nullable()
        ->after('tryon_region')
        ->comment('Groups visually identical variants');

      $table->string('source_image', 255)
        ->nullable()
        ->after('visual_key')
        ->comment('Relative path of the PNG/JPG used for AI try-on');

      $table->text('tryon_notes')
        ->nullable()
        ->after('source_image')
        ->comment('Optional AI prompt notes');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('product_variants', function (Blueprint $table) {

      $table->dropColumn([
        'tryon_enabled',
        'tryon_region',
        'visual_key',
        'source_image',
        'tryon_notes',
      ]);
    });
  }
};
