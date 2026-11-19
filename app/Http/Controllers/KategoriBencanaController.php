<?php

namespace App\Http\Controllers;

use App\Http\Requests\KategoriBencanaRequest;
use App\Models\KategoriBencana;

class KategoriBencanaController extends Controller
{
    public function index()
    {
        $kategori = KategoriBencana::orderBy('nama')->paginate(10);
        return view('kategori_bencana.index', compact('kategori'));
    }

    public function create()
    {
        return view('kategori_bencana.create');
    }

    public function store(KategoriBencanaRequest $request)
    {
        KategoriBencana::create($request->validated());
        return redirect()->route('kategori-bencana.index')
            ->with('success', 'Kategori bencana berhasil ditambahkan.');
    }

    public function edit(KategoriBencana $kategori_bencana)
    {
        return view('kategori_bencana.edit', ['kategori' => $kategori_bencana]);
    }

    public function update(KategoriBencanaRequest $request, KategoriBencana $kategori_bencana)
    {
        $kategori_bencana->update($request->validated());
        return redirect()->route('kategori-bencana.index')
            ->with('success', 'Kategori bencana berhasil diperbarui.');
    }

    public function destroy(KategoriBencana $kategori_bencana)
    {
        if ($kategori_bencana->sudahDipakai()) {
            $kategori_bencana->update(['aktif' => false]);
            return redirect()->route('kategori-bencana.index')
                ->with('success', 'Kategori sudah dipakai pada laporan, jadi dinonaktifkan (tidak dihapus).');
        }

        $kategori_bencana->delete();
        return redirect()->route('kategori-bencana.index')
            ->with('success', 'Kategori bencana berhasil dihapus.');
    }
}