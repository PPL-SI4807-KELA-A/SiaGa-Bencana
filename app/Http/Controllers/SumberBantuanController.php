<?php

namespace App\Http\Controllers;

use App\Http\Requests\SumberBantuanRequest;
use App\Models\SumberBantuan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SumberBantuanController extends Controller
{
    public function index(): View
    {
        $sumberBantuans = SumberBantuan::query()
            ->orderBy('jenis')
            ->orderBy('nama')
            ->paginate(10);

        return view('sumber_bantuan.index', compact('sumberBantuans'));
    }

    public function create(): View
    {
        return view('sumber_bantuan.create', [
            'jenisList' => SumberBantuan::JENIS,
        ]);
    }

    public function store(SumberBantuanRequest $request): RedirectResponse
    {
        SumberBantuan::create([
            ...$request->validated(),
            'aktif' => true,
        ]);

        return redirect()
            ->route('sumber-bantuan.index')
            ->with('success', 'Sumber bantuan berhasil ditambahkan.');
    }

    public function edit(SumberBantuan $sumberBantuan): View
    {
        return view('sumber_bantuan.edit', [
            'sumberBantuan' => $sumberBantuan,
            'jenisList' => SumberBantuan::JENIS,
        ]);
    }

    public function update(SumberBantuanRequest $request, SumberBantuan $sumberBantuan): RedirectResponse
    {
        $sumberBantuan->update($request->validated());

        return redirect()
            ->route('sumber-bantuan.index')
            ->with('success', 'Sumber bantuan berhasil diperbarui.');
    }

    public function destroy(SumberBantuan $sumberBantuan): RedirectResponse
    {
        if ($sumberBantuan->sudahDipakai()) {
            $sumberBantuan->update(['aktif' => false]);

            return redirect()
                ->route('sumber-bantuan.index')
                ->with('success', 'Sumber bantuan yang sudah dipakai tidak dapat dihapus dan telah dinonaktifkan.');
        }

        $sumberBantuan->delete();

        return redirect()
            ->route('sumber-bantuan.index')
            ->with('success', 'Sumber bantuan berhasil dihapus.');
    }
}