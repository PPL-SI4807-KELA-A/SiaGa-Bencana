<?php

namespace App\Http\Controllers;

use App\Http\Requests\WilayahTerdampakRequest;
use App\Models\WilayahTerdampak;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WilayahTerdampakController extends Controller
{
    public function index(Request $request): View
    {
        $kecamatan = $request->query('kecamatan');
        $statusPenanganan = $request->query('status_penanganan');
        $filterKecamatan = in_array($kecamatan, WilayahTerdampak::KECAMATAN, true) ? $kecamatan : null;
        $filterStatus = in_array($statusPenanganan, WilayahTerdampak::STATUS_PENANGANAN, true) ? $statusPenanganan : null;

        $wilayahTerdampak = WilayahTerdampak::query()
            ->when($filterKecamatan, fn ($query) => $query->where('kecamatan', $filterKecamatan))
            ->when($filterStatus, fn ($query) => $query->where('status_penanganan', $filterStatus))
            ->orderByDesc('tanggal_kejadian')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('wilayah_terdampak.index', [
            'wilayahTerdampak' => $wilayahTerdampak,
            'kecamatanList' => WilayahTerdampak::KECAMATAN,
            'statusList' => WilayahTerdampak::STATUS_PENANGANAN,
            'filterKecamatan' => $filterKecamatan,
            'filterStatus' => $filterStatus,
            'hasFilters' => $filterKecamatan !== null || $filterStatus !== null,
        ]);
    }

    public function create(): View
    {
        return view('wilayah_terdampak.create', [
            'desaPerKecamatan' => WilayahTerdampak::DESA_PER_KECAMATAN,
            'kecamatanList' => WilayahTerdampak::KECAMATAN,
            'statusList' => WilayahTerdampak::STATUS_PENANGANAN,
        ]);
    }

    public function store(WilayahTerdampakRequest $request): RedirectResponse
    {
        WilayahTerdampak::create($request->validated());

        return redirect()
            ->route('wilayah-terdampak.index')
            ->with('success', 'Data wilayah terdampak berhasil ditambahkan.');
    }

    public function show(WilayahTerdampak $wilayahTerdampak): View
    {
        return view('wilayah_terdampak.show', compact('wilayahTerdampak'));
    }

    public function edit(WilayahTerdampak $wilayahTerdampak): View
    {
        return view('wilayah_terdampak.edit', [
            'wilayahTerdampak' => $wilayahTerdampak,
            'desaPerKecamatan' => WilayahTerdampak::DESA_PER_KECAMATAN,
            'kecamatanList' => WilayahTerdampak::KECAMATAN,
            'statusList' => WilayahTerdampak::STATUS_PENANGANAN,
        ]);
    }

    public function update(WilayahTerdampakRequest $request, WilayahTerdampak $wilayahTerdampak): RedirectResponse
    {
        $wilayahTerdampak->update($request->validated());

        return redirect()
            ->route('wilayah-terdampak.index')
            ->with('success', 'Data wilayah terdampak berhasil diperbarui.');
    }

    public function destroy(WilayahTerdampak $wilayahTerdampak): RedirectResponse
    {
        $wilayahTerdampak->delete();

        return redirect()
            ->route('wilayah-terdampak.index')
            ->with('success', 'Data wilayah terdampak berhasil dihapus.');
    }
}
