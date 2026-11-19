@extends('layouts.app')
@section('judul', 'Perbaiki Laporan')
@section('subjudul', $laporan->kode_pelacakan)

@section('content')
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
@endsection