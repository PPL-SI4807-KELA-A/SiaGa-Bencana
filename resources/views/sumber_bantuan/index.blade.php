@extends('layouts.app')

@section('title', 'Sumber Bantuan')
@section('judul', 'Sumber Bantuan')
@section('subjudul', 'Kelola pemberi bantuan agar asal setiap bantuan dapat ditelusuri')
@section('menu_aktif', 'sumber')

@section('content')
<div class="container-fluid px-0" style="max-width: 1200px;">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="status">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('sumber-bantuan.create') }}" class="btn btn-sg">
            <i class="bi bi-plus-lg me-1"></i>Tambah Sumber Bantuan
        </a>
    </div>

    <div class="sg-card overflow-hidden">
        @if ($sumberBantuans->isEmpty())
            <div class="text-center text-body-secondary py-5">
                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                Belum ada data sumber bantuan.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jenis</th>
                            <th>Kontak</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sumberBantuans as $sumber)
                            <tr>
                                <td>{{ $sumberBantuans->firstItem() + $loop->index }}</td>
                                <td class="fw-semibold">{{ $sumber->nama }}</td>
                                <td>{{ ucfirst($sumber->jenis) }}</td>
                                <td>{{ $sumber->kontak }}</td>
                                <td>
                                    <span class="badge {{ $sumber->aktif ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $sumber->aktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="text-end text-nowrap">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('sumber-bantuan.edit', $sumber) }}" aria-label="Ubah {{ $sumber->nama }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('sumber-bantuan.destroy', $sumber) }}" class="d-inline" onsubmit="return confirm('Hapus sumber bantuan ini? Sumber yang sudah dipakai akan dinonaktifkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit" aria-label="Hapus {{ $sumber->nama }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mt-3">{{ $sumberBantuans->links('pagination::bootstrap-5') }}</div>
</div>
@endsection