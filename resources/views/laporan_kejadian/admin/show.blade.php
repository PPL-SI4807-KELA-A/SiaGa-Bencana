@extends('layouts.app')
@section('title', 'Detail Laporan Kejadian')
@section('judul', 'Detail Laporan Kejadian')
@section('subjudul', $laporan->kode_pelacakan)
@section('menu_aktif', 'kejadian')

@section('content')
<a href="{{ route('laporan-kejadian.index') }}" class="btn btn-sm btn-outline-secondary mb-3">&larr; Kembali</a>

<div class="sg-card p-4">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <h4 class="mb-0">{{ $laporan->kode_pelacakan }}</h4>
        <span class="badge bg-warning text-dark">{{ $laporan->status }}</span>
    </div>

    <dl class="row mb-0">
        <dt class="col-sm-3">Kategori</dt>
        <dd class="col-sm-9">{{ $laporan->kategori->nama ?? '-' }}</dd>

        <dt class="col-sm-3">Waktu kejadian</dt>
        <dd class="col-sm-9">{{ $laporan->waktu_kejadian->format('d/m/Y H:i') }}</dd>

        <dt class="col-sm-3">Desa/kelurahan</dt>
        <dd class="col-sm-9">{{ $laporan->desa_kelurahan }}</dd>

        <dt class="col-sm-3">Lokasi</dt>
        <dd class="col-sm-9">{{ $laporan->lokasi }}</dd>

        @if($laporan->kategori?->isBanjir())
            <dt class="col-sm-3">Ketinggian air</dt>
            <dd class="col-sm-9">{{ $laporan->ketinggian_air ?? '-' }}</dd>
        @endif

        <dt class="col-sm-3">Deskripsi</dt>
        <dd class="col-sm-9">{{ $laporan->deskripsi }}</dd>

        <dt class="col-sm-3">Pelapor</dt>
        <dd class="col-sm-9">{{ $laporan->nama_pelapor ?? '-' }} ({{ $laporan->kontak_pelapor }})</dd>
    </dl>

    @if($laporan->foto)
        <hr>
        <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto kejadian"
             class="img-fluid rounded" style="max-height: 350px">
    @endif
</div>
@endsection