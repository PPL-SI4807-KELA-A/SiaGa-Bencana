@extends('layouts.app')

@section('title', 'Wilayah Terdampak')

@section('content')
{{-- Sementara: hapus baris ini setelah CHORE: Layout Bersama memuat Bootstrap di layouts.app --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

@php
    $warnaStatus = [
        'Siaga' => 'bg-warning text-dark',
        'Tanggap Darurat' => 'bg-danger',
        'Pemulihan' => 'bg-success',
    ];
@endphp

<div class="container-fluid px-0" style="max-width: 1100px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Data Wilayah Terdampak</h1>
        <a href="{{ route('wilayah-terdampak.create') }}" class="btn btn-primary">Tambah Wilayah</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success" role="status">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            @if ($wilayahTerdampak->isEmpty())
                <p class="text-center text-muted py-4 mb-0">Belum ada data wilayah terdampak.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Desa/Kelurahan</th>
                                <th>Kecamatan</th>
                                <th>Tanggal Kejadian</th>
                                <th class="text-end">Jumlah KK Terdampak</th>
                                <th>Status Penanganan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($wilayahTerdampak as $wilayah)
                                <tr>
                                    <td>{{ $wilayahTerdampak->firstItem() + $loop->index }}</td>
                                    <td>{{ $wilayah->desa_kelurahan }}</td>
                                    <td>{{ $wilayah->kecamatan }}</td>
                                    <td>{{ $wilayah->tanggal_kejadian->locale('id')->translatedFormat('j F Y') }}</td>
                                    <td class="text-end">{{ number_format($wilayah->jumlah_kk_terdampak, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge {{ $warnaStatus[$wilayah->status_penanganan] ?? 'bg-secondary' }}">
                                            {{ $wilayah->status_penanganan }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-3">{{ $wilayahTerdampak->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
