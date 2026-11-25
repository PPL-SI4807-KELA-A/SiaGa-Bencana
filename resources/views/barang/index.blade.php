@extends('layouts.app')

@section('title', 'Data Barang')
@section('judul', 'Data Barang')
@section('subjudul', 'Kelola daftar barang bantuan')

@section('content')
<div class="container-fluid px-0" style="max-width: 1200px;">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="status">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('barang.create') }}" class="btn btn-sg">
            <i class="bi bi-plus-lg me-1"></i>Tambah Barang
        </a>
    </div>

    <div class="sg-card overflow-hidden">
        @if ($barangs->isEmpty())
            <div class="text-center text-body-secondary py-5">
                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                Belum ada data barang.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barangs as $barang)
                            <tr>
                                <td>{{ $barangs->firstItem() + $loop->index }}</td>
                                <td class="fw-semibold">{{ $barang->nama }}</td>
                                <td>{{ ucfirst($barang->kategori) }}</td>
                                <td>{{ $barang->satuan }}</td>
                                <td>
                                    <span class="badge {{ $barang->aktif ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $barang->aktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="text-end text-nowrap">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('barang.edit', $barang) }}" aria-label="Ubah {{ $barang->nama }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('barang.destroy', $barang) }}" class="d-inline" onsubmit="return confirm('Hapus barang ini? Barang yang sudah dipakai akan dinonaktifkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit" aria-label="Hapus {{ $barang->nama }}">
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

    <div class="mt-3">{{ $barangs->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
