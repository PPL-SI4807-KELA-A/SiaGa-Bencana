<?php

namespace App\Http\Controllers;

use App\Http\Requests\BarangRequest;
use App\Models\Barang;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BarangController extends Controller
{
    public function index(): View
    {
        $barangs = Barang::query()
            ->orderBy('kategori')
            ->orderBy('nama')
            ->paginate(10);

        return view('barang.index', compact('barangs'));
    }

    public function create(): View
    {
        return view('barang.create', [
            'kategoriList' => Barang::KATEGORI,
        ]);
    }

    public function store(BarangRequest $request): RedirectResponse
    {
        Barang::create([
            ...$request->validated(),
            'aktif' => true,
        ]);

        return redirect()
            ->route('barang.index')
            ->with('success', 'Data barang berhasil ditambahkan.');
    }

    public function edit(Barang $barang): View
    {
        return view('barang.edit', [
            'barang' => $barang,
            'kategoriList' => Barang::KATEGORI,
        ]);
    }

    public function update(BarangRequest $request, Barang $barang): RedirectResponse
    {
        $barang->update($request->validated());

        return redirect()
            ->route('barang.index')
            ->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang): RedirectResponse
    {
        if ($barang->sudahDipakai()) {
            $barang->update(['aktif' => false]);

            return redirect()
                ->route('barang.index')
                ->with('success', 'Barang yang sudah dipakai tidak dapat dihapus dan telah dinonaktifkan.');
        }

        $barang->delete();

        return redirect()
            ->route('barang.index')
            ->with('success', 'Data barang berhasil dihapus.');
    }
}
