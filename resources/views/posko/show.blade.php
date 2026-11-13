@extends('layouts.app')

@section('title', 'Detail Posko')
@section('judul', 'Detail Posko Pengungsian')
@section('subjudul', 'Informasi posko dan daya tampung')
@section('menu_aktif', 'posko')

@push('styles')
<style>.posko-page .btn:not(.btn-sm) { font-size: .8125rem; border-radius: 8px; padding: .5rem 1rem; }</style>
@endpush

@section('content')
<div class="container-fluid px-0 posko-page" style="max-width: 900px;">
    <div class="sg-card p-4">
        <h2 class="h4 mb-4">{{ $posko->nama_posko }}</h2>
        <dl class="row mb-4">
            <dt class="col-sm-4">Alamat</dt><dd class="col-sm-8">{{ $posko->alamat }}</dd>
            <dt class="col-sm-4">Desa/Kelurahan</dt><dd class="col-sm-8">{{ $posko->desa_kelurahan }}</dd>
            <dt class="col-sm-4">Kecamatan</dt><dd class="col-sm-8">{{ $posko->kecamatan }}</dd>
            <dt class="col-sm-4">Penanggung Jawab</dt><dd class="col-sm-8">{{ $posko->penanggung_jawab }}</dd>
            <dt class="col-sm-4">Kontak Penanggung Jawab</dt><dd class="col-sm-8">{{ $posko->kontak_penanggung_jawab ?: '-' }}</dd>
            <dt class="col-sm-4">Kapasitas</dt><dd class="col-sm-8">{{ number_format($posko->kapasitas, 0, ',', '.') }}</dd>
            <dt class="col-sm-4">Jumlah Terisi</dt><dd class="col-sm-8">{{ number_format($posko->jumlah_terisi, 0, ',', '.') }}</dd>
            <dt class="col-sm-4">Sisa Kapasitas</dt><dd class="col-sm-8">{{ number_format($posko->sisa_kapasitas, 0, ',', '.') }}</dd>
            <dt class="col-sm-4">Dibuat</dt><dd class="col-sm-8">{{ $posko->created_at?->locale('id')->translatedFormat('j F Y, H:i') ?? '-' }}</dd>
            <dt class="col-sm-4">Terakhir Diubah</dt><dd class="col-sm-8">{{ $posko->updated_at?->locale('id')->translatedFormat('j F Y, H:i') ?? '-' }}</dd>
        </dl>
        <div class="d-flex gap-2">
            <a href="{{ route('posko.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
            <a href="{{ route('posko.edit', $posko) }}" class="btn btn-sg"><i class="bi bi-pencil me-1"></i>Ubah</a>
        </div>
    </div>
</div>
@endsection
