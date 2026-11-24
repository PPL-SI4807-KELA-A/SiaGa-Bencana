<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermintaanMendesakRequest;
use App\Models\PermintaanMendesak;
use App\Models\Posko;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermintaanMendesakController extends Controller
{
    public function index(): View
    {
        $permintaanMendesak = PermintaanMendesak::query()
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('permintaan_mendesak.index', compact('permintaanMendesak'));
    }

    public function create(): View
    {
        return view('permintaan_mendesak.create', [
            'daftarPosko' => $this->daftarPoskoAktif(),
        ]);
    }

    public function store(PermintaanMendesakRequest $request): RedirectResponse
    {
        $permintaan = PermintaanMendesak::create($request->validated());

        return redirect()
            ->route('permintaan-mendesak.show', $permintaan)
            ->with('success', 'Permintaan berhasil diajukan.')
            ->with('kode_pelacakan', $permintaan->kode_pelacakan);
    }

    public function lacak(): View
    {
        return view('permintaan_mendesak.track');
    }

    public function prosesLacak(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_pelacakan' => ['required', 'string', 'regex:/^PMD-[A-Z0-9]{8}$/'],
        ], [
            'kode_pelacakan.required' => 'Kode pelacakan wajib diisi.',
            'kode_pelacakan.regex' => 'Format kode pelacakan tidak valid.',
        ]);

        $permintaan = PermintaanMendesak::query()
            ->where('kode_pelacakan', $validated['kode_pelacakan'])
            ->first();

        if ($permintaan === null) {
            return back()->withErrors([
                'kode_pelacakan' => 'Permintaan dengan kode tersebut tidak ditemukan.',
            ]);
        }

        return redirect()->route('permintaan-mendesak.show', $permintaan);
    }

    public function show(PermintaanMendesak $kode_pelacakan): View
    {
        return view('permintaan_mendesak.show', [
            'permintaan' => $kode_pelacakan,
        ]);
    }

    public function edit(PermintaanMendesak $kode_pelacakan): View
    {
        abort_unless($kode_pelacakan->status === PermintaanMendesak::STATUS_DIAJUKAN, 403);

        return view('permintaan_mendesak.edit', [
            'permintaan' => $kode_pelacakan,
            'daftarPosko' => $this->daftarPoskoAktif($kode_pelacakan),
        ]);
    }

    public function update(PermintaanMendesakRequest $request, PermintaanMendesak $kode_pelacakan): RedirectResponse
    {
        abort_unless($kode_pelacakan->status === PermintaanMendesak::STATUS_DIAJUKAN, 403);

        $kode_pelacakan->update($request->validated());

        return redirect()
            ->route('permintaan-mendesak.show', $kode_pelacakan)
            ->with('success', 'Permintaan berhasil diperbarui.');
    }

    public function destroy(PermintaanMendesak $kode_pelacakan): RedirectResponse
    {
        abort_unless($kode_pelacakan->status === PermintaanMendesak::STATUS_DIAJUKAN, 403);

        $kode_pelacakan->delete();

        return redirect()
            ->route('permintaan-mendesak.lacak')
            ->with('success', 'Permintaan berhasil dibatalkan.');
    }

    private function daftarPoskoAktif(?PermintaanMendesak $permintaan = null)
    {
        $daftarPosko = Posko::query()
            ->where('is_aktif', true)
            ->orderBy('nama_posko')
            ->pluck('nama_posko');

        if ($permintaan?->posko !== null && ! $daftarPosko->containsStrict($permintaan->posko)) {
            // Pertahankan pilihan lama yang nonaktif saat edit; request hanya akan menerima nilai lama ini.
            $daftarPosko->push($permintaan->posko);
        }

        return $daftarPosko;
    }
}
