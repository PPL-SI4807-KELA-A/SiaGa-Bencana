<?php

namespace Database\Seeders;

use App\Models\WilayahTerdampak;
use Illuminate\Database\Seeder;

class WilayahTerdampakSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['desa_kelurahan' => 'Bojongsoang', 'kecamatan' => 'Bojongsoang', 'tanggal_kejadian' => '2025-11-03', 'jumlah_kk_terdampak' => 484, 'status_penanganan' => 'Tanggap Darurat', 'keterangan' => 'Data simulasi.'],
            ['desa_kelurahan' => 'Tegalluar', 'kecamatan' => 'Bojongsoang', 'tanggal_kejadian' => '2025-11-03', 'jumlah_kk_terdampak' => 120, 'status_penanganan' => 'Siaga', 'keterangan' => 'Data simulasi.'],
            ['desa_kelurahan' => 'Cipagalo', 'kecamatan' => 'Bojongsoang', 'tanggal_kejadian' => '2025-11-02', 'jumlah_kk_terdampak' => 76, 'status_penanganan' => 'Pemulihan', 'keterangan' => 'Data simulasi.'],
            ['desa_kelurahan' => 'Dayeuhkolot', 'kecamatan' => 'Dayeuhkolot', 'tanggal_kejadian' => '2025-11-03', 'jumlah_kk_terdampak' => 250, 'status_penanganan' => 'Tanggap Darurat', 'keterangan' => 'Data simulasi.'],
            ['desa_kelurahan' => 'Cangkuang Kulon', 'kecamatan' => 'Dayeuhkolot', 'tanggal_kejadian' => '2025-11-02', 'jumlah_kk_terdampak' => 95, 'status_penanganan' => 'Siaga', 'keterangan' => 'Data simulasi.'],
            ['desa_kelurahan' => 'Pasawahan', 'kecamatan' => 'Dayeuhkolot', 'tanggal_kejadian' => '2025-11-01', 'jumlah_kk_terdampak' => 42, 'status_penanganan' => 'Pemulihan', 'keterangan' => 'Data simulasi.'],
        ];

        foreach ($rows as $row) {
            WilayahTerdampak::updateOrCreate(
                ['desa_kelurahan' => $row['desa_kelurahan'], 'tanggal_kejadian' => $row['tanggal_kejadian']],
                $row,
            );
        }
    }
}
