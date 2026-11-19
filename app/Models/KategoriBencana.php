<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class KategoriBencana extends Model
{
    protected $table = 'kategori_bencana';

    protected $fillable = ['nama', 'keterangan', 'aktif'];

    protected $casts = ['aktif' => 'boolean'];

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function sudahDipakai(): bool
    {
        return Schema::hasTable('laporan_kejadian')
            && DB::table('laporan_kejadian')
                ->where('kategori_bencana_id', $this->id)
                ->exists();
    }
}