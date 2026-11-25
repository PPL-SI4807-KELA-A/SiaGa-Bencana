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
        $user = Auth::user();
        $admin = Pengungsi::adalahAdmin($user);
        abort_unless($admin || Pengungsi::bolehKelola($user), 403);

        // Relawan: selalu posko miliknya. Admin: semua posko, boleh disaring lewat ?posko=
        $poskoId = $admin ? ((int) $request->query('posko') ?: null) : (int) $user->posko_id;
        $dasar = fn () => Pengungsi::query()->when($poskoId, fn ($q) => $q->where('posko_id', $poskoId));

        $cari = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $pengungsi = $dasar()
            ->when($cari !== '', function ($q) use ($cari) {
                $like = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $cari).'%';
                $q->where(fn ($w) => $w->where('nama', 'like', $like)->orWhere('alamat_asal', 'like', $like));
            })
            ->when(in_array($status, Pengungsi::STATUS, true), fn ($q) => $q->where('status', $status))
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $perUsia = $dasar()
            ->where('status', 'Aktif')
            ->selectRaw('kelompok_usia, count(*) as total')
            ->groupBy('kelompok_usia')
            ->pluck('total', 'kelompok_usia');

        $posko = $poskoId ? Pengungsi::infoPosko($poskoId) : null;
        $namaPosko = Pengungsi::daftarNamaPosko();

        $daftarPosko = [];
        if ($admin) {
            foreach (Pengungsi::query()->distinct()->orderBy('posko_id')->pluck('posko_id') as $id) {
                $daftarPosko[$id] = $namaPosko[$id] ?? 'Posko #'.$id;
            }
        }

        return view('pengungsi.index', [
            'pengungsi' => $pengungsi,
            'admin' => $admin,
            'bisaKelola' => ! $admin,
            'posko' => $posko,
            'poskoId' => $poskoId,
            'subjudul' => $posko['nama'] ?? 'Semua posko',
            'totalAktif' => $dasar()->where('status', 'Aktif')->count(),
            'namaPosko' => $namaPosko,
            'daftarPosko' => $daftarPosko,
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
            'posko_id' => (int) Auth::user()->posko_id,
            'dicatat_oleh' => Auth::id(),
        ]);

        return redirect()
            ->route('pengungsi.index')
            ->with('success', 'Data pengungsi berhasil ditambahkan.');
    }

    public function update(PengungsiRequest $request, Pengungsi $pengungsi): RedirectResponse
    {
        abort_unless((int) $pengungsi->posko_id === (int) Auth::user()->posko_id, 404);

        $pengungsi->update($request->validated());

        return redirect()
            ->route('pengungsi.index')
            ->with('success', 'Data pengungsi berhasil diperbarui.');
    }

    public function destroy(Pengungsi $pengungsi): RedirectResponse
    {
        abort_unless(Pengungsi::bolehKelola(Auth::user()), 403);
        abort_unless((int) $pengungsi->posko_id === (int) Auth::user()->posko_id, 404);

        $pengungsi->delete();

        return redirect()
            ->route('pengungsi.index')
            ->with('success', 'Data pengungsi berhasil dihapus.');
    }

    /**
     * ID pengungsi aktif dengan catatan kondisi medis Berat (tabel PBI-11).
     * Kosong bila tabel kondisi_medis belum ada. Tanpa foreign key.
     *
     * @return array<int, int>
     */
    private function idKondisiBerat(?int $poskoId): array
    {
        if (! Schema::hasTable('kondisi_medis')
            || ! Schema::hasColumns('kondisi_medis', ['pengungsi_id', 'tingkat_kondisi'])) {
            return [];
        }

        return DB::table('kondisi_medis')
            ->join('pengungsi', 'pengungsi.id', '=', 'kondisi_medis.pengungsi_id')
            ->when($poskoId, fn ($q) => $q->where('pengungsi.posko_id', $poskoId))
            ->where('pengungsi.status', 'Aktif')
            ->where('kondisi_medis.tingkat_kondisi', 'Berat')
            ->distinct()
            ->pluck('kondisi_medis.pengungsi_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }
}
