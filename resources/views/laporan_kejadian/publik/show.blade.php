@extends('layouts.guest')
@section('title', 'Status Laporan')

@section('content')
<div class="container py-4" style="max-width: 800px">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    <div class="card">
        <div class="card-body">
            <p class="text-muted mb-1">Kode pelacakan (simpan untuk memantau laporan Anda)</p>
            <div class="d-flex align-items-center gap-3 mb-3">
                <h3 class="mb-0">{{ $laporan->kode_pelacakan }}</h3>
                <span class="badge bg-warning text-dark">{{ $laporan->status }}</span>
            </div>

            <dl class="row">
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
            </dl>

            @if($laporan->foto)
                <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto kejadian"
                     class="img-fluid rounded mb-3" style="max-height: 300px">
            @endif

            @unless($laporan->sudahDiverifikasi())
                <div>
                    <a href="{{ route('laporan-kejadian.publik.edit', $laporan->kode_pelacakan) }}"
                       class="btn btn-warning">Perbaiki</a>
                    <form action="{{ route('laporan-kejadian.publik.destroy', $laporan->kode_pelacakan) }}"
                          method="POST" class="d-inline"
                          onsubmit="return confirm('Batalkan laporan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Batalkan Laporan</button>
                    </form>
                </div>
            @endunless
        </div>
    </div>
</div>
@endsection