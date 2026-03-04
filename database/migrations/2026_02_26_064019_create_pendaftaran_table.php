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
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wilayah_cover_id')->constrained('wilayah_cover')->onDelete('cascade');
            $table->foreignId('panic_button_id')->nullable();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('no_hp');
            $table->string('email')->unique();
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan']);
            $table->string('nik', 16)->unique();
            $table->string('ttl');
            $table->text('alamat');
            $table->string('RT', 5);
            $table->string('RW', 5);
            $table->string('GetBlockID');
            $table->string('GetNumber');
            $table->string('desa');
            $table->string('kecamatan');
            $table->string('kelurahan');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longtitude', 11, 8);
            $table->string('foto_ktp');
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
            $table->timestamp('waktu_verifikasi')->nullable();
            $table->text('catatan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_foto');
        Schema::dropIfExists('alarm_panic_button');
        Schema::dropIfExists('tugas_admin');
        Schema::dropIfExists('gambar_data_pelanggan');
        Schema::dropIfExists('lokasi_panic_button');
        Schema::dropIfExists('panic_button');
        Schema::dropIfExists('pelanggan');
        Schema::dropIfExists('wilayah_cover');
        Schema::dropIfExists('pendaftaran');
        Schema::dropIfExists('users');
    }
};
