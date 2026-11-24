@extends('layouts.guest')
@section('title', 'Perbaiki Laporan')

@section('content')
<div class="container py-4" style="max-width: 800px">
    <h4>Perbaiki Laporan {{ $laporan->kode_pelacakan }}</h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('laporan-kejadian.publik.update', $laporan->kode_pelacakan) }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('laporan_kejadian.publik._form')
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('laporan-kejadian.publik.show', $laporan->kode_pelacakan) }}"
                   class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection