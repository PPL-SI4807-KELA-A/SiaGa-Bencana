<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Pengungsi extends Model
{
    public const JENIS_KELAMIN = ['Laki-laki', 'Perempuan'];

    public const KELOMPOK_USIA = ['Balita', 'Anak', 'Dewasa', 'Lansia'];

    public const STATUS = ['Aktif', 'Pindah Posko', 'Kembali ke Rumah', 'Dirujuk'];

    public const WARNA_STATUS = [
        'Aktif' => 'pill-aktif',
        'Pindah Posko' => 'pill-warn',
        'Dirujuk' => 'pill-warn',
        'Kembali ke Rumah' => 'pill-muted',
    ];

    /**
     * Sementara belum ada login Relawan dan tabel posko (PBI-02 milik Dhafa),
     * semua pengungsi dicatat di satu posko dummy ini.
     */
    public const POSKO_DUMMY_ID = 1;

    public const POSKO_DUMMY = [
        'nama' => 'Posko Taman Air Bojongsoang',
        'wilayah' => 'Kec. Bojongsoang',
        'kapasitas' => 250,
    ];

    protected $table = 'pengungsi';

    protected $fillable = [
        'posko_id',
        'nik',
        'nama',
        'jenis_kelamin',
        'kelompok_usia',
        'alamat_asal',
        'status',
        'dicatat_oleh',
    ];

    protected function nama(): Attribute
    {
        return Attribute::make(set: fn ($v) => trim((string) $v));
    }

    protected function alamatAsal(): Attribute
    {
        return Attribute::make(set: fn ($v) => trim((string) $v));
    }

    protected function nik(): Attribute
    {
        return Attribute::make(set: fn ($v) => ($v === null || trim((string) $v) === '') ? null : trim((string) $v));
    }

    /**
     * Ringkasan kapasitas posko. Memakai tabel `posko` (PBI-02) bila sudah ada,
     * kalau belum memakai data dummy di POSKO_DUMMY. Tidak ada foreign key.
     */
    public static function infoPosko(int $poskoId): array
    {
        $nama = self::POSKO_DUMMY['nama'];
        $wilayah = self::POSKO_DUMMY['wilayah'];
        $kapasitas = self::POSKO_DUMMY['kapasitas'];
        $beroperasi = true;

        if (Schema::hasTable('posko')
            && Schema::hasColumns('posko', ['id', 'nama', 'kapasitas_maksimum', 'beroperasi'])) {
            $row = DB::table('posko')->where('id', $poskoId)->first();

            if ($row) {
                $nama = $row->nama;
                $kapasitas = (int) $row->kapasitas_maksimum;
                $beroperasi = (bool) $row->beroperasi;
                $wilayah = '';
            }
        }

        $terisi = static::query()->where('posko_id', $poskoId)->where('status', 'Aktif')->count();
        $sisa = max($kapasitas - $terisi, 0);
        $persen = $kapasitas > 0 ? (int) round($terisi / $kapasitas * 100) : 0;

        if ($kapasitas > 0 && $terisi >= $kapasitas) {
            $label = 'Penuh';
        } elseif ($kapasitas > 0 && $terisi * 100 >= $kapasitas * 80) {
            $label = 'Hampir Penuh';
        } else {
            $label = 'Siap';
        }

        return compact('nama', 'wilayah', 'kapasitas', 'beroperasi', 'terisi', 'sisa', 'persen', 'label');
    }
}
