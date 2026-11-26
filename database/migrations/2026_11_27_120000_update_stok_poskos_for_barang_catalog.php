<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stok_poskos', function (Blueprint $table) {
            $table->string('kode_barang', 50)->nullable()->change();
            $table->unique(['posko_id', 'barang_id'], 'stok_poskos_posko_id_barang_id_unique');
        });
    }

    public function down(): void
    {
        DB::table('stok_poskos')
            ->whereNull('kode_barang')
            ->orderBy('id')
            ->get(['id'])
            ->each(function (object $stok): void {
                $kode = 'LEGACY-' . $stok->id;
                $suffix = 1;

                while (DB::table('stok_poskos')->where('kode_barang', $kode)->exists()) {
                    $kode = 'LEGACY-' . $stok->id . '-' . $suffix++;
                }

                DB::table('stok_poskos')->where('id', $stok->id)->update(['kode_barang' => $kode]);
            });

        Schema::table('stok_poskos', function (Blueprint $table) {
            $table->dropUnique('stok_poskos_posko_id_barang_id_unique');
            $table->string('kode_barang', 50)->nullable(false)->change();
        });
    }
};
