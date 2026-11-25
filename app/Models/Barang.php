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

    private const TABEL_STOK = 'stok_poskos';

    private const KOLOM_PENGHUBUNG_STOK = 'barang_id';

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
