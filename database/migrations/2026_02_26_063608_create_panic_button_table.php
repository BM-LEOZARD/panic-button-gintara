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
        Schema::create('panic_button', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade');
            $table->foreignId('wilayah_id')->constrained('wilayah_cover')->onDelete('cascade');
            $table->string('DisID')->nullable();
            $table->string('GUID')->unique()->nullable();
            $table->string('GetBlockID')->nullable();
            $table->string('GetNumber')->nullable();
            $table->enum('state', ['Aman', 'Darurat'])->default('Aman');
            $table->unsignedBigInteger('timestamp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panic_button');
    }
};
