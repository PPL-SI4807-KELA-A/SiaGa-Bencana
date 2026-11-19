<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan_mendesak', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pelacakan', 12)->unique();
            $table->string('jenis_kebutuhan', 100);
            $table->unsignedInteger('perkiraan_jumlah');
            $table->string('lokasi', 255);
            $table->string('nomor_kontak', 16);
            $table->string('posko')->nullable();
            $table->string('status')->default('Diajukan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_mendesak');
    }
};
