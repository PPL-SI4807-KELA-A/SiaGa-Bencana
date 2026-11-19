<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sumber_bantuan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jenis', 50);
            $table->string('kontak');
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->unique(['nama', 'jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sumber_bantuan');
    }
};