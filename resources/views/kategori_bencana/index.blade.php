@extends('layouts.app')
@section('title', 'Kategori Bencana')
@section('judul', 'Kategori Bencana')
@section('subjudul', 'Jenis bencana yang dipakai pada formulir laporan kejadian')
@section('menu_aktif', 'kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Daftar Kategori</h5>
    <a href="{{ route('kategori-bencana.create') }}" class="btn btn-sg">+ Tambah Kategori</a>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<div class="sg-card p-3">
    <table class="table table-striped align-middle mb-0">
        <thead>
            <tr><th>Nama</th><th>Keterangan</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        @forelse($kategori as $k)
            <tr>
                <td>{{ $k->nama }}</td>
                <td>{{ $k->keterangan ?? '-' }}</td>
                <td>
                    <span class="badge {{ $k->aktif ? 'bg-success' : 'bg-secondary' }}">
                        {{ $k->aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('kategori-bencana.edit', $k) }}" class="btn btn-sm btn-warning">Ubah</a>
                    <form action="{{ route('kategori-bencana.destroy', $k) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Hapus kategori ini? Jika sudah dipakai, akan dinonaktifkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted">Belum ada kategori.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $kategori->links() }}</div>
@endsection