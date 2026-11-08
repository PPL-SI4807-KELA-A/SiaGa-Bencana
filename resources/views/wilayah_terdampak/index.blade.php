@extends('layouts.app')

@section('title', 'Wilayah Terdampak')
@section('judul', 'Wilayah Terdampak')
@section('subjudul', 'Kelola data wilayah terdampak bencana')
@section('menu_aktif', 'wilayah')

@push('styles')
<style>
    /* Samakan ukuran tombol sekunder DAHIF-1 dengan tombol btn-sg dari layout bersama */
    .wilayah-page .btn:not(.btn-sm) { font-size: .8125rem; border-radius: 8px; padding: .5rem 1rem; }
</style>
@endpush

@section('content')
<div class="container-fluid px-0 wilayah-page" style="max-width: 1200px;">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="status">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('wilayah-terdampak.create') }}" class="btn btn-sg">
            <i class="bi bi-plus-lg me-1"></i>Tambah Wilayah
        </a>
    </div>

    <div class="sg-card p-3 mb-3">
        <form method="GET" action="{{ route('wilayah-terdampak.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="filter-kecamatan" class="form-label">Kecamatan</label>
                <select id="filter-kecamatan" name="kecamatan" class="form-select">
                    <option value="">Semua kecamatan</option>
                    @foreach ($kecamatanList as $kecamatan)
                        <option value="{{ $kecamatan }}" @selected($filterKecamatan === $kecamatan)>{{ $kecamatan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="filter-status" class="form-label">Status Penanganan</label>
                <select id="filter-status" name="status_penanganan" class="form-select">
                    <option value="">Semua status</option>
                    @foreach ($statusList as $status)
                        <option value="{{ $status }}" @selected($filterStatus === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-sg"><i class="bi bi-funnel me-1"></i>Terapkan</button>
                <a href="{{ route('wilayah-terdampak.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="sg-card overflow-hidden">
        @if ($wilayahTerdampak->isEmpty())
            <div class="text-center text-body-secondary py-5">
                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                {{ $hasFilters ? 'Tidak ada data yang sesuai filter.' : 'Belum ada data wilayah terdampak.' }}
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Desa/Kelurahan</th>
                            <th>Kecamatan</th>
                            <th>Tanggal Kejadian</th>
                            <th class="text-end">Jumlah KK</th>
                            <th>Status Penanganan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($wilayahTerdampak as $wilayah)
                            <tr>
                                <td>{{ $wilayahTerdampak->firstItem() + $loop->index }}</td>
                                <td class="fw-semibold">{{ $wilayah->desa_kelurahan }}</td>
                                <td>{{ $wilayah->kecamatan }}</td>
                                <td>{{ $wilayah->tanggal_kejadian->locale('id')->translatedFormat('j F Y') }}</td>
                                <td class="text-end">{{ number_format($wilayah->jumlah_kk_terdampak, 0, ',', '.') }}</td>
                                <td><span class="badge {{ \App\Models\WilayahTerdampak::WARNA_STATUS[$wilayah->status_penanganan] ?? 'bg-secondary' }}">{{ $wilayah->status_penanganan }}</span></td>
                                <td class="text-end text-nowrap">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('wilayah-terdampak.show', $wilayah) }}" aria-label="Detail {{ $wilayah->desa_kelurahan }}"><i class="bi bi-eye"></i></a>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('wilayah-terdampak.edit', $wilayah) }}" aria-label="Ubah {{ $wilayah->desa_kelurahan }}"><i class="bi bi-pencil"></i></a>
                                    <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#hapusWilayah{{ $wilayah->id }}" aria-label="Hapus {{ $wilayah->desa_kelurahan }}"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Modal hapus ditempatkan di luar tabel agar tidak mewarisi gaya sel tabel --}}
            @foreach ($wilayahTerdampak as $wilayah)
                @include('wilayah_terdampak._hapus_modal', ['wilayahTerdampak' => $wilayah])
            @endforeach
        @endif
    </div>

    <div class="mt-3">{{ $wilayahTerdampak->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
