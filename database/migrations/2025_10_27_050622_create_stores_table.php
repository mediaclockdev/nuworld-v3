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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->unsignedBigInteger('country_id');
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode', 20)->nullable();
            $table->text('location')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('phone')->nullable();
            $table->string('image')->nullable();

            $table->tinyInteger('status')->default('1')->comment('0 for Inactive; 1 for Active');
            $table->foreignId('created_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('admins');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
