<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class WilayahTerdampak extends Model
{
    public const KECAMATAN = ['Bojongsoang', 'Dayeuhkolot'];

    public const STATUS_PENANGANAN = ['Siaga', 'Tanggap Darurat', 'Pemulihan'];

    public const DESA_PER_KECAMATAN = [
        'Bojongsoang' => ['Bojongsari', 'Bojongsoang', 'Buahbatu', 'Cipagalo', 'Lengkong', 'Tegalluar'],
        'Dayeuhkolot' => ['Cangkuang Kulon', 'Cangkuang Wetan', 'Citeureup', 'Dayeuhkolot', 'Pasawahan', 'Sukapura'],
    ];

    protected $table = 'wilayah_terdampak';

    protected $fillable = [
        'desa_kelurahan',
        'kecamatan',
        'tanggal_kejadian',
        'jumlah_kk_terdampak',
        'status_penanganan',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kejadian' => 'date',
        ];
    }

    /**
     * Simpan tanggal kejadian sebagai Y-m-d agar konsisten di MySQL dan SQLite.
     */
    protected function tanggalKejadian(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value === null ? null : Carbon::parse($value)->toDateString(),
        );
    }
}
