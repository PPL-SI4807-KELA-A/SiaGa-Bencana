<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Pengungsi extends Model
{
    public const JENIS_KELAMIN = ['Laki-laki', 'Perempuan'];

    public const KELOMPOK_USIA = ['Balita', 'Anak', 'Dewasa', 'Lansia'];

    public const STATUS = ['Aktif', 'Pindah Posko', 'Kembali ke Rumah', 'Dirujuk ke Fasilitas Kesehatan'];

    public const WARNA_STATUS = [
        'Aktif' => 'pill-aktif',
        'Pindah Posko' => 'pill-warn',
        'Dirujuk ke Fasilitas Kesehatan' => 'pill-warn',
        'Kembali ke Rumah' => 'pill-muted',
    ];

    public const PERAN_ADMIN = 'Admin BPBD';

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

    public static function adalahAdmin($user): bool
    {
        return $user !== null && $user->role === self::PERAN_ADMIN;
    }

    /** Boleh tambah/ubah/hapus: akun non-Admin yang punya posko_id (Relawan Posko). */
    public static function bolehKelola($user): bool
    {
        return $user !== null && ! self::adalahAdmin($user) && (int) $user->posko_id > 0;
    }

    /** Peta id posko => nama posko, dibaca dari tabel posko. */
    public static function daftarNamaPosko(): array
    {
        return Posko::query()->pluck('nama_posko', 'id')->all();
    }

    /**
     * Ringkasan kapasitas satu posko. Membaca kolom nama_posko, kapasitas, dan
     * status_operasional dari tabel posko (PBI-02), tanpa foreign key.
     */
    public static function infoPosko(int $poskoId): array
    {
        $posko = Posko::query()->find($poskoId);

        $ditemukan = $posko !== null;
        $nama = $posko?->nama_posko ?? 'Posko #'.$poskoId;
        $kapasitas = (int) ($posko?->kapasitas ?? 0);
        $beroperasi = $posko?->status_operasional === 'Beroperasi';

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

        return compact('ditemukan', 'nama', 'kapasitas', 'beroperasi', 'terisi', 'sisa', 'persen', 'label');
    }
}
