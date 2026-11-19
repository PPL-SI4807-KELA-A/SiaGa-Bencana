<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LaporanKejadian extends Model
{
    public const MENUNGGU = 'Menunggu Verifikasi';

    protected $table = 'laporan_kejadian';

    protected $fillable = [
        'kode_pelacakan', 'kategori_bencana_id', 'waktu_kejadian', 'lokasi',
        'desa_kelurahan', 'ketinggian_air', 'deskripsi', 'nama_pelapor',
        'kontak_pelapor', 'foto',
    ];

    protected $casts = ['waktu_kejadian' => 'datetime'];

    public function kategori()
    {
        return $this->belongsTo(KategoriBencana::class, 'kategori_bencana_id');
    }

    public static function buatKode(): string
    {
        do {
            $kode = 'LAP-' . now()->format('ym') . '-' . str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (static::where('kode_pelacakan', $kode)->exists());

        return $kode;
    }

    // Status dibaca dari verifikasi terakhir (tabel milik PBI-18), tidak disimpan di sini.
    public function getStatusAttribute(): string
    {
        if (! Schema::hasTable('verifikasi_laporan')) {
            return self::MENUNGGU;
        }

        $hasil = DB::table('verifikasi_laporan')
            ->where('laporan_kejadian_id', $this->id)
            ->orderByDesc('id')
            ->value('hasil');

        return $hasil ?: self::MENUNGGU;
    }

    public function sudahDiverifikasi(): bool
    {
        return $this->status !== self::MENUNGGU;
    }
}