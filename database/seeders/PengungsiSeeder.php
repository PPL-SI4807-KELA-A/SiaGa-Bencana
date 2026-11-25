<?php

namespace Database\Seeders;

use App\Models\Pengungsi;
use Illuminate\Database\Seeder;

/**
 * Data simulasi. Posko 1 dan 2 sesuai akun Relawan di PetugasSeeder
 * (posko_id 1 dan 2). Jalankan: php artisan db:seed --class=PengungsiSeeder
 */
class PengungsiSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [1, 'Siti Aminah', 'Perempuan', 'Lansia', 'Kp. Cijagra RT 03/RW 07, Citeureup', 'Aktif', '3204000000000001'],
            [1, 'Muhammad Rizky', 'Laki-laki', 'Balita', 'Perum Bumi Bojongsoang Asri Blok C2', 'Dirujuk ke Fasilitas Kesehatan', null],
            [1, 'Ujang Sopandi', 'Laki-laki', 'Lansia', 'Kp. Bojongsoang RT 01/RW 04', 'Aktif', '3204000000000003'],
            [1, 'Dewi Lestari', 'Perempuan', 'Dewasa', 'Kp. Lengkong RT 05/RW 02, Lengkong', 'Aktif', '3204000000000004'],
            [1, 'Euis Nurhayati', 'Perempuan', 'Dewasa', 'Kp. Lengkong RT 05/RW 02, Lengkong', 'Aktif', null],
            [1, 'Nadia Putri', 'Perempuan', 'Anak', 'Kp. Lengkong RT 05/RW 02, Lengkong', 'Aktif', null],
            [1, 'Asep Saepudin', 'Laki-laki', 'Dewasa', 'Kp. Bojong Citepus RT 02/RW 09, Bojongsari', 'Pindah Posko', '3204000000000007'],
            [1, 'Rafi Ahmad', 'Laki-laki', 'Anak', 'Kp. Bojong Citepus RT 02/RW 09, Bojongsari', 'Pindah Posko', null],
            [1, 'Aisyah Zahra', 'Perempuan', 'Balita', 'Kp. Cijagra RT 03/RW 07, Citeureup', 'Aktif', null],
            [1, 'Cecep Hidayat', 'Laki-laki', 'Dewasa', 'Kp. Tegalluar RT 04/RW 03, Tegalluar', 'Kembali ke Rumah', '3204000000000010'],
            [2, 'Wawan Setiawan', 'Laki-laki', 'Dewasa', 'Kp. Cipagalo RT 02/RW 01, Cipagalo', 'Aktif', '3204000000000011'],
            [2, 'Neneng Rohaeti', 'Perempuan', 'Lansia', 'Kp. Pasawahan RT 06/RW 02, Pasawahan', 'Aktif', null],
            [2, 'Dimas Prakoso', 'Laki-laki', 'Anak', 'Kp. Citeureup RT 01/RW 03, Dayeuhkolot', 'Aktif', null],
            [2, 'Lilis Suryani', 'Perempuan', 'Dewasa', 'Kp. Citeureup RT 01/RW 03, Dayeuhkolot', 'Aktif', '3204000000000014'],
        ];

        foreach ($rows as [$poskoId, $nama, $jk, $usia, $alamat, $status, $nik]) {
            Pengungsi::updateOrCreate(
                ['posko_id' => $poskoId, 'nama' => $nama, 'alamat_asal' => $alamat],
                ['jenis_kelamin' => $jk, 'kelompok_usia' => $usia, 'status' => $status, 'nik' => $nik],
            );
        }
    }
}
