<?php

namespace App\Services;

use App\Models\StokPosko;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StokService
{
    public function tambahBarang(array $data): StokPosko
    {
        $payload = Arr::only($data, [
            'posko_id', 'nama_posko', 'kode_barang', 'nama_barang',
            'kategori', 'satuan', 'jumlah_stok', 'batas_minimum',
        ]);

        if (($payload['jumlah_stok'] ?? 0) < 0 || ($payload['batas_minimum'] ?? 0) < 0) {
            throw ValidationException::withMessages([
                'jumlah_stok' => 'Jumlah stok dan batas minimum tidak boleh negatif.',
            ]);
        }

        try {
            return StokPosko::create($payload + ['memiliki_transaksi' => false]);
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages([
                'kode_barang' => 'Barang ini sudah ada di daftar stok posko.',
            ]);
        }
    }

    public function ubahBatasMinimum(StokPosko $stok, int $batasMinimum): StokPosko
    {
        if ($batasMinimum < 0) {
            throw ValidationException::withMessages([
                'batas_minimum' => 'Batas minimum tidak boleh negatif.',
            ]);
        }

        $stok->update(['batas_minimum' => $batasMinimum]);

        return $stok;
    }

    /** Hapus barang dari daftar stok, hanya jika belum ada transaksi. */
    public function hapusBarang(StokPosko $stok): void
    {
        DB::transaction(function () use ($stok) {
            $terkini = StokPosko::whereKey($stok->id)->lockForUpdate()->first();

            if (! $terkini) {
                return;
            }

            if ($terkini->memiliki_transaksi) {
                throw ValidationException::withMessages([
                    'hapus' => 'Barang tidak dapat dihapus karena sudah memiliki transaksi.',
                ]);
            }

            $terkini->delete();
        });
    }
}