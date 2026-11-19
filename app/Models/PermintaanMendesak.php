<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PermintaanMendesak extends Model
{
    public const STATUS_DIAJUKAN = 'Diajukan';

    public const DAFTAR_POSKO = [
        'Posko Dayeuhkolot',
        'Posko Lembang',
        'Posko Rancaekek',
    ];

    protected $table = 'permintaan_mendesak';

    protected $fillable = [
        'jenis_kebutuhan',
        'perkiraan_jumlah',
        'lokasi',
        'nomor_kontak',
        'posko',
    ];

    protected function casts(): array
    {
        return [
            'perkiraan_jumlah' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'kode_pelacakan';
    }

    protected static function booted(): void
    {
        static::creating(function (PermintaanMendesak $permintaan): void {
            if (blank($permintaan->kode_pelacakan)) {
                $permintaan->kode_pelacakan = static::buatKodePelacakanUnik();
            }

            if (blank($permintaan->status)) {
                $permintaan->status = self::STATUS_DIAJUKAN;
            }
        });
    }

    private static function buatKodePelacakanUnik(): string
    {
        do {
            $kode = 'PMD-'.Str::upper(Str::random(8));
        } while (static::query()->where('kode_pelacakan', $kode)->exists());

        return $kode;
    }
}
