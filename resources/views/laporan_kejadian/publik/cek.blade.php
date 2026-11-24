@extends('layouts.guest')
@section('title', 'Cek Status Laporan')

@section('content')
<div class="container py-4" style="max-width: 800px">
    <h4>Cek Status Laporan</h4>
    <p class="text-muted">Masukkan kode pelacakan yang Anda terima saat mengirim laporan.</p>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($tidakDitemukan)
        <div class="alert alert-danger">Kode "{{ $kode }}" tidak ditemukan.</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('laporan-kejadian.publik.cek') }}" method="GET" class="row g-2">
                <div class="col-md-8">
                    <input type="text" name="kode" value="{{ $kode }}" class="form-control"
                           placeholder="Contoh: LAP-2611-0101">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Cek</button>
                </div>
                <div class="col-auto">
                    <a href="{{ route('laporan-kejadian.publik.create') }}" class="btn btn-outline-secondary">Buat Laporan Baru</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection