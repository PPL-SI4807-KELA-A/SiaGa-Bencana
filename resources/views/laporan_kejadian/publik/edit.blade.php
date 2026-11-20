@extends('layouts.guest')
@section('title', 'Perbaiki Laporan')
@section('menu_aktif', 'lapor-kejadian')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-4">
        <h1 class="h3 mb-1">Perbaiki Laporan</h1>
        <p class="text-body-secondary mb-0">{{ $laporan->kode_pelacakan }}</p>
    </div>

    <div class="sg-card p-4">
        <form action="{{ route('laporan-kejadian.publik.update', $laporan->kode_pelacakan) }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('laporan_kejadian.publik._form')
            <button type="submit" class="btn btn-sg">Simpan Perubahan</button>
            <a href="{{ route('laporan-kejadian.publik.show', $laporan->kode_pelacakan) }}"
               class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
