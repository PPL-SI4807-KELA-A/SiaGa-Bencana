@extends('layouts.app')

@section('title', 'Detail Wilayah Terdampak')
@section('judul', 'Detail Wilayah Terdampak')
@section('subjudul', 'Informasi lengkap wilayah terdampak')
@section('menu_aktif', 'wilayah')

@push('styles')
<style>
    /* Samakan ukuran tombol sekunder DAHIF-1 dengan tombol btn-sg dari layout bersama */
    .wilayah-page .btn:not(.btn-sm) { font-size: .8125rem; border-radius: 8px; padding: .5rem 1rem; }
</style>
@endpush

@section('content')
<div class="container-fluid px-0 wilayah-page" style="max-width: 900px;">
    <div class="sg-card p-4">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h2 class="h4 mb-1">{{ $wilayahTerdampak->desa_kelurahan }}</h2>
                <div class="text-body-secondary">Kecamatan {{ $wilayahTerdampak->kecamatan }}</div>
            </div>
            <span class="badge {{ \App\Models\WilayahTerdampak::WARNA_STATUS[$wilayahTerdampak->status_penanganan] ?? 'bg-secondary' }}">{{ $wilayahTerdampak->status_penanganan }}</span>
        </div>

        <dl class="row mb-4">
            <dt class="col-sm-4">Tanggal Kejadian</dt>
            <dd class="col-sm-8">{{ $wilayahTerdampak->tanggal_kejadian->locale('id')->translatedFormat('j F Y') }}</dd>
            <dt class="col-sm-4">Jumlah KK Terdampak</dt>
            <dd class="col-sm-8">{{ number_format($wilayahTerdampak->jumlah_kk_terdampak, 0, ',', '.') }}</dd>
            <dt class="col-sm-4">Keterangan</dt>
            <dd class="col-sm-8">{{ $wilayahTerdampak->keterangan ?: '-' }}</dd>
            <dt class="col-sm-4">Dibuat</dt>
            <dd class="col-sm-8">{{ $wilayahTerdampak->created_at?->timezone('Asia/Jakarta')->locale('id')->translatedFormat('j F Y, H:i') . ' WIB' ?? '-' }}</dd>
            <dt class="col-sm-4">Terakhir Diubah</dt>
            <dd class="col-sm-8">{{ $wilayahTerdampak->updated_at?->timezone('Asia/Jakarta')->locale('id')->translatedFormat('j F Y, H:i') . ' WIB' ?? '-' }}</dd>
        </dl>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('wilayah-terdampak.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
            <a href="{{ route('wilayah-terdampak.edit', $wilayahTerdampak) }}" class="btn btn-sg"><i class="bi bi-pencil me-1"></i>Ubah</a>
            <button class="btn btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#hapusWilayah{{ $wilayahTerdampak->id }}"><i class="bi bi-trash me-1"></i>Hapus</button>
        </div>
    </div>
    @include('wilayah_terdampak._hapus_modal', ['wilayahTerdampak' => $wilayahTerdampak])
</div>
@endsection
