@extends('layouts.guest')
@section('title', 'Lapor Kejadian Bencana')
@section('menu_aktif', 'lapor-kejadian')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-4">
        <h1 class="h3 mb-1">Lapor Kejadian Bencana</h1>
        <p class="text-body-secondary mb-0">Laporan diverifikasi Admin BPBD sebelum ditindaklanjuti. Tanpa perlu akun.</p>
    </div>

    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <p class="mb-1">Periksa kembali data yang Anda masukkan:</p>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="sg-card p-4">
        <form action="{{ route('laporan-kejadian.publik.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('laporan_kejadian.publik._form')
            <button type="submit" class="btn btn-sg">Kirim Laporan</button>
            <a href="{{ route('laporan-kejadian.publik.cek') }}" class="btn btn-outline-secondary">Cek Kode</a>
        </form>
    </div>
</div>
@endsection
