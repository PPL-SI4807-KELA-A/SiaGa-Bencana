<?php

namespace Database\Seeders;

use App\Models\StokPosko;
use Illuminate\Database\Seeder;

class StokPoskoSeeder extends Seeder
{
    /**
     * Data contoh mandiri untuk PBI-05.
     * Jalankan manual: php artisan db:seed --class=StokPoskoSeeder
     */
    public function run(): void
    {
        $rows = [
            [
                'posko_id' => 1,
                'nama_posko' => 'Posko Utama',
                'kode_barang' => 'BRG-001',
                'nama_barang' => 'Beras Medium',
                'kategori' => 'pangan',
                'satuan' => 'karung',
                'jumlah_stok' => 15,
                'batas_minimum' => 20, // menipis: 15 <= 20
                'memiliki_transaksi' => true,
            ],
            [
                'posko_id' => 1,
                'nama_posko' => 'Posko Utama',
                'kode_barang' => 'BRG-002',
                'nama_barang' => 'Air Mineral Kemasan',
                'kategori' => 'pangan',
                'satuan' => 'karton',
                'jumlah_stok' => 120,
                'batas_minimum' => 30, // aman: 120 > 30
                'memiliki_transaksi' => false,
            ],
            [
                'posko_id' => 1,
                'nama_posko' => 'Posko Utama',
                'kode_barang' => 'OBT-001',
                'nama_barang' => 'Paracetamol 500mg',
                'kategori' => 'obat-obatan',
                'satuan' => 'box',
                'jumlah_stok' => 10,
                'batas_minimum' => 10, // batas pas (menipis): 10 <= 10
                'memiliki_transaksi' => false,
            ],
            [
                'posko_id' => 2,
                'nama_posko' => 'Posko Sektor Utara',
                'kode_barang' => 'SND-001',
                'nama_barang' => 'Selimut Tebal',
                'kategori' => 'sandang',
                'satuan' => 'pcs',
                'jumlah_stok' => 8,
                'batas_minimum' => 25, // menipis: 8 <= 25
                'memiliki_transaksi' => false,
            ],
        ];

        foreach ($rows as $row) {
            StokPosko::updateOrCreate(
                ['posko_id' => $row['posko_id'], 'kode_barang' => $row['kode_barang']],
                $row
            );
        }
    }
}