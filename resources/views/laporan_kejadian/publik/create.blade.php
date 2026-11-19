@extends('layouts.app')
@section('judul', 'Lapor Kejadian Bencana')
@section('subjudul', 'Laporan diverifikasi Admin BPBD sebelum ditindaklanjuti. Tanpa perlu akun.')

@section('content')
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

<div class="sg-card p-4">
    <form action="{{ route('laporan-kejadian.publik.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('laporan_kejadian.publik._form')
        <button type="submit" class="btn btn-sg">Kirim Laporan</button>
        <a href="{{ route('laporan-kejadian.publik.cek') }}" class="btn btn-outline-secondary">Cek Kode</a>
    </form>
</div>
@endsection