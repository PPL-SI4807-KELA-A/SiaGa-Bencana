<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wilayah_terdampak', function (Blueprint $table) {
            $table->id();
            $table->string('desa_kelurahan', 100);
            $table->enum('kecamatan', ['Bojongsoang', 'Dayeuhkolot']);
            $table->date('tanggal_kejadian');
            $table->unsignedInteger('jumlah_kk_terdampak');
            $table->enum('status_penanganan', ['Siaga', 'Tanggap Darurat', 'Pemulihan']);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['desa_kelurahan', 'tanggal_kejadian']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayah_terdampak');
    }
};
