<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) lebarkan pilihan dulu supaya nilai baru boleh dipakai
        Schema::table('pengungsi', function (Blueprint $table) {
            $table->enum('status', ['Aktif', 'Pindah Posko', 'Kembali ke Rumah', 'Dirujuk', 'Dirujuk ke Fasilitas Kesehatan'])
                ->default('Aktif')->change();
        });

        // 2) ubah data lama
        DB::table('pengungsi')->where('status', 'Dirujuk')->update(['status' => 'Dirujuk ke Fasilitas Kesehatan']);

        // 3) rapikan pilihan: nilai lama dihapus
        Schema::table('pengungsi', function (Blueprint $table) {
            $table->enum('status', ['Aktif', 'Pindah Posko', 'Kembali ke Rumah', 'Dirujuk ke Fasilitas Kesehatan'])
                ->default('Aktif')->change();
        });
    }

    public function down(): void
    {
        Schema::table('pengungsi', function (Blueprint $table) {
            $table->enum('status', ['Aktif', 'Pindah Posko', 'Kembali ke Rumah', 'Dirujuk', 'Dirujuk ke Fasilitas Kesehatan'])
                ->default('Aktif')->change();
        });

        DB::table('pengungsi')->where('status', 'Dirujuk ke Fasilitas Kesehatan')->update(['status' => 'Dirujuk']);

        Schema::table('pengungsi', function (Blueprint $table) {
            $table->enum('status', ['Aktif', 'Pindah Posko', 'Kembali ke Rumah', 'Dirujuk'])
                ->default('Aktif')->change();
        });
    }
};
