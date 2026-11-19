<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_kejadian', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pelacakan', 20)->unique();
            $table->unsignedBigInteger('kategori_bencana_id')->index();
            $table->dateTime('waktu_kejadian');
            $table->string('lokasi', 255);
            $table->string('desa_kelurahan', 100);
            $table->string('ketinggian_air', 50)->nullable();
            $table->text('deskripsi');
            $table->string('nama_pelapor', 100)->nullable();
            $table->string('kontak_pelapor', 20);
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_kejadian');
    }
};