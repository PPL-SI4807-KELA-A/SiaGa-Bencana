<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Posko extends Model
{
    public const KECAMATAN = WilayahTerdampak::KECAMATAN;

    public const DESA_PER_KECAMATAN = WilayahTerdampak::DESA_PER_KECAMATAN;

    protected $table = 'posko';

    protected $fillable = [
        'nama_posko',
        'alamat',
        'desa_kelurahan',
        'kecamatan',
        'penanggung_jawab',
        'kontak_penanggung_jawab',
        'kapasitas',
        'jumlah_terisi',
    ];

    protected function casts(): array
    {
        return [
            'kapasitas' => 'integer',
            'jumlah_terisi' => 'integer',
            'is_aktif' => 'boolean',
        ];
    }

    protected function sisaKapasitas(): Attribute
    {
        return Attribute::get(fn () => max(0, $this->kapasitas - $this->jumlah_terisi));
    }
}
