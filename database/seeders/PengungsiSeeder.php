<?php

namespace Database\Seeders;

use App\Models\Pengungsi;
use Illuminate\Database\Seeder;

class PengungsiSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['Siti Aminah', 'Perempuan', 'Lansia', 'Kp. Cijagra RT 03/RW 07, Citeureup', 'Aktif', '3204000000000001'],
            ['Muhammad Rizky', 'Laki-laki', 'Balita', 'Perum Bumi Bojongsoang Asri Blok C2', 'Dirujuk', null],
            ['Ujang Sopandi', 'Laki-laki', 'Lansia', 'Kp. Bojongsoang RT 01/RW 04', 'Aktif', '3204000000000003'],
            ['Dewi Lestari', 'Perempuan', 'Dewasa', 'Kp. Lengkong RT 05/RW 02, Lengkong', 'Aktif', '3204000000000004'],
            ['Euis Nurhayati', 'Perempuan', 'Dewasa', 'Kp. Lengkong RT 05/RW 02, Lengkong', 'Aktif', null],
            ['Nadia Putri', 'Perempuan', 'Anak', 'Kp. Lengkong RT 05/RW 02, Lengkong', 'Aktif', null],
            ['Asep Saepudin', 'Laki-laki', 'Dewasa', 'Kp. Bojong Citepus RT 02/RW 09, Bojongsari', 'Pindah Posko', '3204000000000007'],
            ['Rafi Ahmad', 'Laki-laki', 'Anak', 'Kp. Bojong Citepus RT 02/RW 09, Bojongsari', 'Pindah Posko', null],
            ['Aisyah Zahra', 'Perempuan', 'Balita', 'Kp. Cijagra RT 03/RW 07, Citeureup', 'Aktif', null],
            ['Cecep Hidayat', 'Laki-laki', 'Dewasa', 'Kp. Tegalluar RT 04/RW 03, Tegalluar', 'Kembali ke Rumah', '3204000000000010'],
            ['Wawan Setiawan', 'Laki-laki', 'Dewasa', 'Kp. Cipagalo RT 02/RW 01, Cipagalo', 'Aktif', '3204000000000011'],
            ['Neneng Rohaeti', 'Perempuan', 'Lansia', 'Kp. Pasawahan RT 06/RW 02, Pasawahan', 'Aktif', null],
        ];

        foreach ($rows as [$nama, $jk, $usia, $alamat, $status, $nik]) {
            Pengungsi::updateOrCreate(
                ['posko_id' => Pengungsi::POSKO_DUMMY_ID, 'nama' => $nama, 'alamat_asal' => $alamat],
                ['jenis_kelamin' => $jk, 'kelompok_usia' => $usia, 'status' => $status, 'nik' => $nik],
            );
        }
    }
}
