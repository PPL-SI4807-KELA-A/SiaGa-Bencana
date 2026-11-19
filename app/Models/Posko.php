<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Posko extends Model
{
    public const KECAMATAN = WilayahTerdampak::KECAMATAN;

    public const DESA_PER_KECAMATAN = WilayahTerdampak::DESA_PER_KECAMATAN;

    public const STATUS_KESIAPAN = ['Siap', 'Hampir Penuh', 'Penuh', 'Tidak Beroperasi'];

    public const WARNA_STATUS_KESIAPAN = [
        'Siap' => 'bg-success',
        'Hampir Penuh' => 'bg-warning text-dark',
        'Penuh' => 'bg-danger',
        'Tidak Beroperasi' => 'bg-secondary',
    ];

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

    protected function persentaseTerisi(): Attribute
    {
        return Attribute::get(fn () => $this->kapasitas > 0
            ? (int) round(($this->jumlah_terisi / $this->kapasitas) * 100)
            : 0);
    }

    protected function statusKesiapan(): Attribute
    {
        return Attribute::get(function (): string {
            if ($this->status_operasional === 'Tidak Beroperasi') {
                return 'Tidak Beroperasi';
            }

            if ($this->kapasitas > 0 && $this->jumlah_terisi >= $this->kapasitas) {
                return 'Penuh';
            }

            if ($this->kapasitas > 0 && $this->jumlah_terisi * 100 >= 80 * $this->kapasitas) {
                return 'Hampir Penuh';
            }

            return 'Siap';
        });
    }
}
