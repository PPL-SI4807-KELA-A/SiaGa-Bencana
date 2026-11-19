@extends('layouts.guest')

@section('title', 'Posko Aktif')
@section('menu_aktif', 'posko-aktif')

@push('styles')
<style>
    .posko-publik-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 290px), 1fr)); gap: 1rem; }
    .posko-publik-card { padding: 1.25rem; height: 100%; }
    .posko-publik-card .progress { height: .55rem; }
    .posko-publik-filter { max-width: 360px; }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="status">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Posko Pengungsian Aktif</h1>
            <p class="text-body-secondary mb-0">Informasi ketersediaan posko untuk warga.</p>
        </div>
        <form method="GET" action="{{ route('posko.publik') }}" class="posko-publik-filter d-flex gap-2">
            <label class="visually-hidden" for="filter-kecamatan">Pilih kecamatan</label>
            <select id="filter-kecamatan" name="kecamatan" class="form-select">
                <option value="">Semua kecamatan</option>
                @foreach ($kecamatanList as $kecamatan)
                    <option value="{{ $kecamatan }}" @selected($filterKecamatan === $kecamatan)>{{ $kecamatan }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sg">Filter</button>
            @if ($hasFilter)
                <a href="{{ route('posko.publik') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </form>
    </div>

    @if ($posko->isEmpty())
        <div class="sg-card text-center p-5 text-body-secondary">
            <i class="bi bi-house-exclamation fs-2 d-block mb-2"></i>
            {{ $hasFilter ? 'Tidak ada posko aktif di kecamatan ini.' : 'Belum ada posko aktif saat ini.' }}
        </div>
    @else
        <div class="posko-publik-grid">
            @foreach ($posko as $item)
                <article class="sg-card posko-publik-card">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                        <div>
                            <h2 class="h5 mb-1">{{ $item->nama_posko }}</h2>
                            <p class="text-body-secondary mb-0">{{ $item->desa_kelurahan }}, {{ $item->kecamatan }}</p>
                        </div>
                        <span class="badge {{ \App\Models\Posko::WARNA_STATUS_KESIAPAN[$item->status_kesiapan] }}">{{ $item->status_kesiapan }}</span>
                    </div>
                    <p class="mb-3"><i class="bi bi-geo-alt me-1"></i>{{ $item->alamat }}</p>
                    @if ($item->status_kesiapan === 'Tidak Beroperasi')
                        {{-- Posko yang tidak beroperasi tidak menerima pengungsi, jadi sisa tempat tidak ditampilkan --}}
                        <p class="text-body-secondary mb-0"><i class="bi bi-slash-circle me-1"></i>Sedang tidak menerima pengungsi.</p>
                    @else
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sisa {{ number_format($item->sisa_kapasitas, 0, ',', '.') }} dari {{ number_format($item->kapasitas, 0, ',', '.') }} tempat</span>
                            <span>{{ $item->persentase_terisi }}%</span>
                        </div>
                        <div class="progress" role="progressbar" aria-label="Keterisian {{ $item->nama_posko }}" aria-valuenow="{{ $item->persentase_terisi }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar {{ \App\Models\Posko::WARNA_STATUS_KESIAPAN[$item->status_kesiapan] }}" style="width: {{ min($item->persentase_terisi, 100) }}%"></div>
                        </div>
                    @endif
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
