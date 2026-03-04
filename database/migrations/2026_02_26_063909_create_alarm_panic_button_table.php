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
        Schema::create('alarm_panic_button', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panic_button_id')->constrained('panic_button')->onDelete('cascade');
            $table->foreignId('lokasi_panic_button_id')->constrained('lokasi_panic_button')->onDelete('cascade');
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('ditangani_oleh')->nullable();
            $table->enum('status', ['Menunggu', 'Diproses', 'Selesai'])->default('Menunggu');
            $table->timestamp('waktu_trigger')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alarm_panic_button');
    }
};
