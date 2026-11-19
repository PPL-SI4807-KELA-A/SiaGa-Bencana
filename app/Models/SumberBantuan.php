<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SumberBantuan extends Model
{
    public const JENIS = [
        'pemerintah',
        'swasta',
        'komunitas',
        'perorangan',
    ];

    private const TABEL_PENERIMAAN = 'penerimaan_logistik';

    // Nama kolom penghubung menunggu konfirmasi PIC PBI-15 (Ibnu). Jangan menebak namanya.
    private const KOLOM_PENGHUBUNG_PENERIMAAN = null;

    protected $table = 'sumber_bantuan';

    protected $fillable = [
        'nama',
        'jenis',
        'kontak',
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
     * Periksa pemakaian pada penerimaan logistik tanpa foreign key ke tabel PBI-15.
     * Kolom penghubung sengaja belum ditetapkan sampai dikonfirmasi PIC PBI-15.
     */
    public function sudahDipakai(): bool
    {
        if (! Schema::hasTable(self::TABEL_PENERIMAAN)) {
            return false;
        }

        $kolomPenghubung = self::KOLOM_PENGHUBUNG_PENERIMAAN;

        if ($kolomPenghubung === null || ! Schema::hasColumn(self::TABEL_PENERIMAAN, $kolomPenghubung)) {
            return false;
        }

        return DB::table(self::TABEL_PENERIMAAN)
            ->where($kolomPenghubung, $this->getKey())
            ->exists();
    }
}