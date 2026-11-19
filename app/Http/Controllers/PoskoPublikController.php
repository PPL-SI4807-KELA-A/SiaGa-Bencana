<?php

namespace App\Http\Controllers;

use App\Models\Posko;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PoskoPublikController extends Controller
{
    public function index(Request $request): View
    {
        $kecamatan = $request->query('kecamatan');
        $filterKecamatan = in_array($kecamatan, Posko::KECAMATAN, true) ? $kecamatan : null;

        $posko = Posko::query()
            ->where('is_aktif', true)
            ->when($filterKecamatan, fn ($query) => $query->where('kecamatan', $filterKecamatan))
            ->orderBy('status_operasional')
            ->orderBy('nama_posko')
            ->get();

        return view('publik.posko', [
            'posko' => $posko,
            'kecamatanList' => Posko::KECAMATAN,
            'filterKecamatan' => $filterKecamatan,
            'hasFilter' => $filterKecamatan !== null,
        ]);
    }
}
