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
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="status">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="sg-card p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <h2 class="h4 mb-0">{{ $posko->nama_posko }}</h2>
            <div class="d-flex gap-2">
                <span class="badge {{ \App\Models\Posko::WARNA_STATUS_KESIAPAN[$posko->status_kesiapan] }}">{{ $posko->status_kesiapan }}</span>
                @unless ($posko->is_aktif)<span class="badge bg-dark">Nonaktif</span>@endunless
            </div>
        </div>
        <dl class="row mb-4">
            <dt class="col-sm-4">Alamat</dt><dd class="col-sm-8">{{ $posko->alamat }}</dd>
            <dt class="col-sm-4">Desa/Kelurahan</dt><dd class="col-sm-8">{{ $posko->desa_kelurahan }}</dd>
            <dt class="col-sm-4">Kecamatan</dt><dd class="col-sm-8">{{ $posko->kecamatan }}</dd>
            <dt class="col-sm-4">Penanggung Jawab</dt><dd class="col-sm-8">{{ $posko->penanggung_jawab }}</dd>
            <dt class="col-sm-4">Kontak Penanggung Jawab</dt><dd class="col-sm-8">{{ $posko->kontak_penanggung_jawab ?: '-' }}</dd>
            <dt class="col-sm-4">Kapasitas</dt><dd class="col-sm-8">{{ number_format($posko->kapasitas, 0, ',', '.') }}</dd>
            <dt class="col-sm-4">Jumlah Terisi</dt><dd class="col-sm-8">{{ number_format($posko->jumlah_terisi, 0, ',', '.') }}</dd>
            <dt class="col-sm-4">Sisa Kapasitas</dt><dd class="col-sm-8">{{ number_format($posko->sisa_kapasitas, 0, ',', '.') }}</dd>
            <dt class="col-sm-4">Persentase Terisi</dt><dd class="col-sm-8">{{ $posko->persentase_terisi }}%</dd>
            <dt class="col-sm-4">Status Operasional</dt><dd class="col-sm-8">{{ $posko->status_operasional }}</dd>
            <dt class="col-sm-4">Status Posko</dt><dd class="col-sm-8">{{ $posko->is_aktif ? 'Aktif' : 'Nonaktif' }}</dd>
            <dt class="col-sm-4">Dibuat</dt><dd class="col-sm-8">{{ $posko->created_at?->locale('id')->translatedFormat('j F Y, H:i') ?? '-' }}</dd>
            <dt class="col-sm-4">Terakhir Diubah</dt><dd class="col-sm-8">{{ $posko->updated_at?->locale('id')->translatedFormat('j F Y, H:i') ?? '-' }}</dd>
        </dl>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('posko.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
            <a href="{{ route('posko.edit', $posko) }}" class="btn btn-sg"><i class="bi bi-pencil me-1"></i>Ubah</a>
            <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalStatusOperasional">
                {{ $posko->status_operasional === 'Beroperasi' ? 'Tandai Tidak Beroperasi' : 'Tandai Beroperasi' }}
            </button>
            <button type="button" class="btn {{ $posko->is_aktif ? 'btn-outline-danger' : 'btn-outline-success' }}" data-bs-toggle="modal" data-bs-target="#modalStatusAktif">
                {{ $posko->is_aktif ? 'Nonaktifkan' : 'Aktifkan Kembali' }}
            </button>
        </div>
    </div>
    @include('posko._status_modal', [
        'modalId' => 'modalStatusOperasional',
        'judul' => $posko->status_operasional === 'Beroperasi' ? 'Tandai Posko Tidak Beroperasi' : 'Tandai Posko Beroperasi',
        'pesan' => $posko->status_operasional === 'Beroperasi' ? 'Posko ini tidak akan menerima pengungsi sampai diaktifkan kembali.' : 'Posko ini akan ditandai beroperasi kembali.',
        'action' => route('posko.status-operasional', $posko),
        'labelAksi' => $posko->status_operasional === 'Beroperasi' ? 'Ya, Tandai Tidak Beroperasi' : 'Ya, Tandai Beroperasi',
        'buttonClass' => 'btn-warning',
    ])
    @include('posko._status_modal', [
        'modalId' => 'modalStatusAktif',
        'judul' => $posko->is_aktif ? 'Nonaktifkan Posko' : 'Aktifkan Kembali Posko',
        'pesan' => $posko->is_aktif ? 'Posko nonaktif tidak akan ditampilkan di halaman publik.' : 'Posko ini akan ditampilkan kembali di halaman publik.',
        'action' => route('posko.aktif', $posko),
        'labelAksi' => $posko->is_aktif ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan Kembali',
        'buttonClass' => $posko->is_aktif ? 'btn-danger' : 'btn-success',
    ])
</div>
@endsection
