<?php

namespace App\Http\Controllers;

use App\Http\Requests\PengungsiRequest;
use App\Models\Pengungsi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PengungsiController extends Controller
{
    public function index(Request $request): View
    {
        $poskoId = Pengungsi::POSKO_DUMMY_ID;
        $cari = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $pengungsi = Pengungsi::query()
            ->where('posko_id', $poskoId)
            ->when($cari !== '', function ($q) use ($cari) {
                $like = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $cari).'%';
                $q->where(fn ($w) => $w->where('nama', 'like', $like)->orWhere('alamat_asal', 'like', $like));
            })
            ->when(in_array($status, Pengungsi::STATUS, true), fn ($q) => $q->where('status', $status))
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $perUsia = Pengungsi::query()
            ->where('posko_id', $poskoId)
            ->where('status', 'Aktif')
            ->selectRaw('kelompok_usia, count(*) as total')
            ->groupBy('kelompok_usia')
            ->pluck('total', 'kelompok_usia');

        return view('pengungsi.index', [
            'pengungsi' => $pengungsi,
            'posko' => Pengungsi::infoPosko($poskoId),
            'perUsia' => $perUsia,
            'beratIds' => $this->idKondisiBerat($poskoId),
            'cari' => $cari,
            'filterStatus' => $status,
            'statusList' => Pengungsi::STATUS,
            'jenisKelaminList' => Pengungsi::JENIS_KELAMIN,
            'usiaList' => Pengungsi::KELOMPOK_USIA,
        ]);
    }

    public function store(PengungsiRequest $request): RedirectResponse
    {
        Pengungsi::create([
            ...$request->validated(),
            'posko_id' => Pengungsi::POSKO_DUMMY_ID,
            'dicatat_oleh' => Auth::id(),
        ]);

        return redirect()
            ->route('pengungsi.index')
            ->with('success', 'Data pengungsi berhasil ditambahkan.');
    }

    public function update(PengungsiRequest $request, Pengungsi $pengungsi): RedirectResponse
    {
        abort_unless($pengungsi->posko_id === Pengungsi::POSKO_DUMMY_ID, 404);

        $pengungsi->update($request->validated());

        return redirect()
            ->route('pengungsi.index')
            ->with('success', 'Data pengungsi berhasil diperbarui.');
    }

    public function destroy(Pengungsi $pengungsi): RedirectResponse
    {
        abort_unless($pengungsi->posko_id === Pengungsi::POSKO_DUMMY_ID, 404);

        $pengungsi->delete();

        return redirect()
            ->route('pengungsi.index')
            ->with('success', 'Data pengungsi berhasil dihapus.');
    }

    /**
     * ID pengungsi aktif dengan catatan kondisi medis Berat (tabel PBI-11).
     * Kosong bila tabel kondisi_medis belum ada, tanpa foreign key.
     *
     * @return array<int, int>
     */
    private function idKondisiBerat(int $poskoId): array
    {
        if (! Schema::hasTable('kondisi_medis')
            || ! Schema::hasColumns('kondisi_medis', ['pengungsi_id', 'tingkat_kondisi'])) {
            return [];
        }

        return DB::table('kondisi_medis')
            ->join('pengungsi', 'pengungsi.id', '=', 'kondisi_medis.pengungsi_id')
            ->where('pengungsi.posko_id', $poskoId)
            ->where('pengungsi.status', 'Aktif')
            ->where('kondisi_medis.tingkat_kondisi', 'Berat')
            ->distinct()
            ->pluck('kondisi_medis.pengungsi_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }
}
