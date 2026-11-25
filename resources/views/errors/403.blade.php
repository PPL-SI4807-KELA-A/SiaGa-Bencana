@extends(auth()->check() ? 'layouts.app' : 'layouts.guest')

@section('title', 'Akses Ditolak')
@section('judul', 'Akses Ditolak')
@section('subjudul', 'Peranmu tidak memiliki izin untuk halaman ini')
@section('menu_aktif', '')

@section('content')
    <div class="sg-card p-5 text-center mx-auto" style="max-width: 520px;">
        <i class="bi bi-shield-lock" style="font-size: 3rem; color: var(--sg-accent);"></i>
        <h2 class="h4 mt-3 mb-2">403 - Akses Ditolak</h2>
        <p class="text-muted mb-4">{{ $exception->getMessage() ?: 'Anda tidak memiliki akses ke halaman ini.' }}</p>
        <a href="{{ auth()->check() ? route('dasbor') : route('posko.publik') }}" class="btn btn-sg">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
@endsection