@extends('layouts.guest')
@section('title', 'Cek Status Laporan')
@section('menu_aktif', 'lapor-kejadian')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-4">
        <h1 class="h3 mb-1">Cek Status Laporan</h1>
        <p class="text-body-secondary mb-0">Masukkan kode pelacakan yang Anda terima saat mengirim laporan.</p>
    </div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($tidakDitemukan)
    <div class="alert alert-danger">Kode "{{ $kode }}" tidak ditemukan.</div>
@endif

<div class="sg-card p-4">
    <form action="{{ route('laporan-kejadian.publik.cek') }}" method="GET" class="row g-2">
        <div class="col-md-6">
            <input type="text" name="kode" value="{{ $kode }}" class="form-control"
                   placeholder="Contoh: LAP-2611-0101">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-sg">Cek</button>
        </div>
        <div class="col-auto">
            <a href="{{ route('laporan-kejadian.publik.create') }}" class="btn btn-outline-secondary">Buat Laporan Baru</a>
        </div>
    </form>
</div>
</div>
@endsection
