<?php

namespace App\Http\Controllers;

use App\Models\LaporanKejadian;

class LaporanKejadianController extends Controller
{
    public function index()
    {
        $laporan = LaporanKejadian::with('kategori')->latest()->paginate(10);
        return view('laporan_kejadian.admin.index', compact('laporan'));
    }

    public function show(LaporanKejadian $laporan)
    {
        $laporan->load('kategori');
        return view('laporan_kejadian.admin.show', compact('laporan'));
    }
}