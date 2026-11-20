<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class StokPosko extends Model
{
    protected $table = 'stok_poskos';

    protected $fillable = [
        'posko_id', 'nama_posko', 'barang_id', 'kode_barang', 'nama_barang',
        'kategori', 'satuan', 'jumlah_stok', 'batas_minimum', 'memiliki_transaksi',
    ];

    protected $casts = [
        'posko_id' => 'integer',
        'jumlah_stok' => 'integer',
        'batas_minimum' => 'integer',
        'memiliki_transaksi' => 'boolean',
    ];

    public function isMenipis(): bool
    {
        return $this->jumlah_stok <= $this->batas_minimum;
    }

    public function scopeMenipis(Builder $query): Builder
    {
        return $query->whereColumn('jumlah_stok', '<=', 'batas_minimum');
    }
}