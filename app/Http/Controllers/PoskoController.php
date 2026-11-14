<?php

namespace App\Http\Controllers;

use App\Http\Requests\PoskoRequest;
use App\Models\Posko;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PoskoController extends Controller
{
    public function index(): View
    {
        $posko = Posko::query()
            ->orderBy('nama_posko')
            ->paginate(10)
            ->withQueryString();

        return view('posko.index', compact('posko'));
    }

    public function create(): View
    {
        return view('posko.create', $this->formOptions());
    }

    public function store(PoskoRequest $request): RedirectResponse
    {
        Posko::create($request->validated());

        return redirect()
            ->route('posko.index')
            ->with('success', 'Data posko berhasil ditambahkan.');
    }

    public function show(Posko $posko): View
    {
        return view('posko.show', compact('posko'));
    }

    public function edit(Posko $posko): View
    {
        return view('posko.edit', array_merge(['posko' => $posko], $this->formOptions()));
    }

    public function update(PoskoRequest $request, Posko $posko): RedirectResponse
    {
        $posko->update($request->validated());

        return redirect()
            ->route('posko.index')
            ->with('success', 'Data posko berhasil diperbarui.');
    }

    public function toggleStatusOperasional(Posko $posko): RedirectResponse
    {
        $posko->status_operasional = $posko->status_operasional === 'Beroperasi'
            ? 'Tidak Beroperasi'
            : 'Beroperasi';
        $posko->save();

        $message = $posko->status_operasional === 'Tidak Beroperasi'
            ? 'Posko ditandai Tidak Beroperasi.'
            : 'Posko ditandai Beroperasi kembali.';

        return redirect()->route('posko.show', $posko)->with('success', $message);
    }

    public function toggleAktif(Posko $posko): RedirectResponse
    {
        $posko->is_aktif = ! $posko->is_aktif;
        $posko->save();

        $message = $posko->is_aktif
            ? 'Posko berhasil diaktifkan kembali.'
            : 'Posko berhasil dinonaktifkan.';

        return redirect()->route('posko.show', $posko)->with('success', $message);
    }

    private function formOptions(): array
    {
        return [
            'kecamatanList' => Posko::KECAMATAN,
            'desaPerKecamatan' => Posko::DESA_PER_KECAMATAN,
        ];
    }
}
