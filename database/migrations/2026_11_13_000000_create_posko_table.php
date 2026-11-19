<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posko', function (Blueprint $table) {
            $table->id();
            $table->string('nama_posko', 150)->unique();
            $table->string('alamat', 255);
            $table->string('desa_kelurahan', 100);
            $table->enum('kecamatan', ['Bojongsoang', 'Dayeuhkolot']);
            $table->string('penanggung_jawab', 100);
            $table->string('kontak_penanggung_jawab', 20)->nullable();
            $table->unsignedInteger('kapasitas');
            $table->unsignedInteger('jumlah_terisi')->default(0);
            $table->enum('status_operasional', ['Beroperasi', 'Tidak Beroperasi'])->default('Beroperasi');
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posko');
    }
};
