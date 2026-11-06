<?php

namespace App\Http\Controllers;

use App\Http\Requests\WilayahTerdampakRequest;
use App\Models\WilayahTerdampak;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WilayahTerdampakController extends Controller
{
    public function index(): View
    {
        $wilayahTerdampak = WilayahTerdampak::query()
            ->orderByDesc('tanggal_kejadian')
            ->paginate(10);

        return view('wilayah_terdampak.index', compact('wilayahTerdampak'));
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
}
