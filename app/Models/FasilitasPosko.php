<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FasilitasPosko extends Model
{
    public const KONDISI = ['Baik', 'Rusak Ringan', 'Rusak Berat'];

    public const WARNA_KONDISI = [
        'Baik' => 'bg-success',
        'Rusak Ringan' => 'bg-warning text-dark',
        'Rusak Berat' => 'bg-danger',
    ];

    public const SARAN_JENIS = [
        'MCK',
        'Tenda',
        'Dapur Umum',
        'Genset',
        'Tempat Tidur Lipat',
        'Tangki Air Bersih',
        'Pos Kesehatan',
    ];

    protected $table = 'fasilitas_posko';

    protected $fillable = [
        'posko_id',
        'jenis_fasilitas',
        'jumlah',
        'kondisi',
        'keterangan',
        'dicatat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'posko_id' => 'integer',
            'dicatat_oleh' => 'integer',
        ];
    }
}
