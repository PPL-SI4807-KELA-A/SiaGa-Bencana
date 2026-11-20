<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_poskos', function (Blueprint $table) {
            $table->id();
         
            $table->unsignedBigInteger('posko_id');
            $table->string('nama_posko');
            $table->unsignedBigInteger('barang_id')->nullable();
            $table->string('kode_barang', 50);
            $table->string('nama_barang');
            $table->string('kategori', 50);
            $table->string('satuan', 30);
            $table->unsignedInteger('jumlah_stok')->default(0);   
            $table->unsignedInteger('batas_minimum')->default(0);
            $table->boolean('memiliki_transaksi')->default(false);
            $table->timestamps();
            $table->unique(['posko_id', 'kode_barang']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_poskos');
    }
};