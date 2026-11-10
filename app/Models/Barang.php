<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Barang extends Model
{
    public const KATEGORI = [
        'pangan',
        'sandang',
        'obat-obatan',
        'perlengkapan bayi',
        'kebersihan',
    ];

    private const TABEL_STOK = 'stok_posko';

    // Nama kolom penghubung menunggu konfirmasi PIC PBI-05. Jangan menebak namanya.
    private const KOLOM_PENGHUBUNG_STOK = null;

    protected $table = 'barang';

    protected $fillable = [
        'nama',
        'kategori',
        'satuan',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function setNamaAttribute(string $value): void
    {
        $this->attributes['nama'] = trim($value);
    }

    /**
     * Periksa pemakaian barang tanpa membuat foreign key ke tabel stok PBI lain.
     * Nilai kolom penghubung sengaja belum ditetapkan sampai dikonfirmasi PIC PBI-05.
     */
    public function sudahDipakai(): bool
    {
        if (! Schema::hasTable(self::TABEL_STOK)) {
            return false;
        }

        $kolomPenghubung = self::KOLOM_PENGHUBUNG_STOK;

        if ($kolomPenghubung === null || ! Schema::hasColumn(self::TABEL_STOK, $kolomPenghubung)) {
            return false;
        }

        return DB::table(self::TABEL_STOK)
            ->where($kolomPenghubung, $this->getKey())
            ->exists();
    }
}
