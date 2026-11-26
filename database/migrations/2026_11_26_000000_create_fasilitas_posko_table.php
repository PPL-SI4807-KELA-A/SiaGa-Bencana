<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fasilitas_posko', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('posko_id')->index();
            $table->string('jenis_fasilitas', 100);
            $table->unsignedInteger('jumlah');
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat']);
            $table->string('keterangan', 255)->nullable();
            $table->unsignedBigInteger('dicatat_oleh')->nullable();
            $table->timestamps();
            $table->unique(['posko_id', 'jenis_fasilitas']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fasilitas_posko');
    }
};
