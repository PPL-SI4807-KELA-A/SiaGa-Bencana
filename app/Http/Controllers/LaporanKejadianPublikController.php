<?php

namespace App\Http\Controllers;

use App\Http\Requests\LaporanKejadianRequest;
use App\Models\KategoriBencana;
use App\Models\LaporanKejadian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanKejadianPublikController extends Controller
{
    private function cari(string $kode): LaporanKejadian
    {
        return LaporanKejadian::with('kategori')
            ->where('kode_pelacakan', strtoupper($kode))
            ->firstOrFail();
    }

    private function kategori()
    {
        return KategoriBencana::aktif()->orderBy('nama')->get();
    }

    public function create()
    {
        return view('laporan_kejadian.publik.create', ['kategori' => $this->kategori()]);
    }

    public function store(LaporanKejadianRequest $request)
    {
        $data = $request->safe()->except('foto');
        $data['kode_pelacakan'] = LaporanKejadian::buatKode();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('laporan-kejadian', 'public');
        }

        $laporan = LaporanKejadian::create($data);

        return redirect()->route('laporan-kejadian.publik.show', $laporan->kode_pelacakan)
            ->with('success', 'Laporan berhasil dikirim. Simpan kode pelacakan Anda.');
    }

    public function cek(Request $request)
    {
        $kode = strtoupper(trim((string) $request->query('kode', '')));

        if ($kode === '') {
            return view('laporan_kejadian.publik.cek', ['kode' => '', 'tidakDitemukan' => false]);
        }

        $laporan = LaporanKejadian::where('kode_pelacakan', $kode)->first();

        if ($laporan) {
            return redirect()->route('laporan-kejadian.publik.show', $laporan->kode_pelacakan);
        }

        return view('laporan_kejadian.publik.cek', ['kode' => $kode, 'tidakDitemukan' => true]);
    }

    public function show(string $kode)
    {
        return view('laporan_kejadian.publik.show', ['laporan' => $this->cari($kode)]);
    }

    public function edit(string $kode)
    {
        $laporan = $this->cari($kode);

        if ($laporan->sudahDiverifikasi()) {
            return redirect()->route('laporan-kejadian.publik.show', $laporan->kode_pelacakan)
                ->with('error', 'Laporan sudah diverifikasi dan tidak dapat diubah.');
        }

        return view('laporan_kejadian.publik.edit', [
            'laporan' => $laporan,
            'kategori' => $this->kategori(),
        ]);
    }

    public function update(LaporanKejadianRequest $request, string $kode)
    {
        $laporan = $this->cari($kode);

        if ($laporan->sudahDiverifikasi()) {
            return redirect()->route('laporan-kejadian.publik.show', $laporan->kode_pelacakan)
                ->with('error', 'Laporan sudah diverifikasi dan tidak dapat diubah.');
        }

        $data = $request->safe()->except('foto');

        if ($request->hasFile('foto')) {
            if ($laporan->foto) {
                Storage::disk('public')->delete($laporan->foto);
            }
            $data['foto'] = $request->file('foto')->store('laporan-kejadian', 'public');
        }

        $laporan->update($data);

        return redirect()->route('laporan-kejadian.publik.show', $laporan->kode_pelacakan)
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(string $kode)
    {
        $laporan = $this->cari($kode);

        if ($laporan->sudahDiverifikasi()) {
            return redirect()->route('laporan-kejadian.publik.show', $laporan->kode_pelacakan)
                ->with('error', 'Laporan sudah diverifikasi dan tidak dapat dibatalkan.');
        }

        if ($laporan->foto) {
            Storage::disk('public')->delete($laporan->foto);
        }
        $laporan->delete();

        return redirect()->route('laporan-kejadian.publik.cek')
            ->with('success', 'Laporan berhasil dibatalkan.');
    }
}