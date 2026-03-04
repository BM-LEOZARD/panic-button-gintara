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
        Schema::create('wilayah_cover', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode_wilayah', 20)->unique();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longtitude', 11, 8);
            $table->integer('radius_meter')->default(100);
            $table->text('alamat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wilayah_cover');
    }
};
