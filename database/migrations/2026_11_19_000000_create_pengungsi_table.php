<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengungsi', function (Blueprint $table) {
            $table->id();
            // Tanpa foreign key: tabel posko dan users milik PBI lain (aturan CONTRIBUTING).
            $table->unsignedBigInteger('posko_id');
            $table->char('nik', 16)->nullable();
            $table->string('nama', 100);
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->enum('kelompok_usia', ['Balita', 'Anak', 'Dewasa', 'Lansia']);
            $table->string('alamat_asal', 255);
            $table->enum('status', ['Aktif', 'Pindah Posko', 'Kembali ke Rumah', 'Dirujuk'])->default('Aktif');
            $table->unsignedBigInteger('dicatat_oleh')->nullable();
            $table->timestamps();

            $table->index(['posko_id', 'status']);
            $table->index('nik');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengungsi');
    }
};
