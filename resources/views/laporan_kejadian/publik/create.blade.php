@extends('layouts.guest')
@section('title', 'Lapor Kejadian Bencana')

@section('content')
<div class="container py-4" style="max-width: 800px">
    <h4>Laporkan Kejadian Bencana</h4>
    <p class="text-muted">Laporan diverifikasi oleh Admin BPBD sebelum ditindaklanjuti. Tanpa perlu akun.</p>

    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('laporan-kejadian.publik.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('laporan_kejadian.publik._form')
                <button type="submit" class="btn btn-primary">Kirim Laporan</button>
                <a href="{{ route('laporan-kejadian.publik.cek') }}" class="btn btn-outline-secondary">Cek Kode</a>
            </form>
        </div>
    </div>
</div>
@endsection