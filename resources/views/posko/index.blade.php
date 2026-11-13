@extends('layouts.app')

@section('title', 'Data Posko')
@section('judul', 'Data Posko Pengungsian')
@section('subjudul', 'Kelola informasi posko dan daya tampungnya')
@section('menu_aktif', 'posko')

@push('styles')
<style>
    .posko-page .btn:not(.btn-sm) { font-size: .8125rem; border-radius: 8px; padding: .5rem 1rem; }
</style>
@endpush

@section('content')
<div class="container-fluid px-0 posko-page" style="max-width: 1200px;">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="status">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('posko.create') }}" class="btn btn-sg"><i class="bi bi-plus-lg me-1"></i>Tambah Posko</a>
    </div>

    <div class="sg-card overflow-hidden">
        @if ($posko->isEmpty())
            <div class="text-center text-body-secondary py-5">
                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                Belum ada data posko.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Posko</th>
                            <th>Lokasi</th>
                            <th>Penanggung Jawab</th>
                            <th class="text-end">Kapasitas</th>
                            <th class="text-end">Terisi</th>
                            <th class="text-end">Sisa</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posko as $item)
                            <tr>
                                <td>{{ $posko->firstItem() + $loop->index }}</td>
                                <td class="fw-semibold">{{ $item->nama_posko }}</td>
                                <td>{{ $item->desa_kelurahan }}, {{ $item->kecamatan }}</td>
                                <td>{{ $item->penanggung_jawab }}</td>
                                <td class="text-end">{{ number_format($item->kapasitas, 0, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($item->jumlah_terisi, 0, ',', '.') }}</td>
                                <td class="text-end fw-semibold">{{ number_format($item->sisa_kapasitas, 0, ',', '.') }}</td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('posko.show', $item) }}" class="btn btn-sm btn-outline-secondary" aria-label="Detail {{ $item->nama_posko }}"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('posko.edit', $item) }}" class="btn btn-sm btn-outline-primary" aria-label="Ubah {{ $item->nama_posko }}"><i class="bi bi-pencil"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mt-3">{{ $posko->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
