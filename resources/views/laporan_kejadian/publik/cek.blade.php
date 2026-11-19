@extends('layouts.app')
@section('judul', 'Cek Status Laporan')
@section('subjudul', 'Masukkan kode pelacakan yang Anda terima saat mengirim laporan')

@section('content')
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
@endsection