@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Kategori Bencana</h4>
    <a href="{{ route('kategori-bencana.create') }}" class="btn btn-primary">+ Tambah Kategori</a>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<table class="table table-striped">
    <thead><tr><th>Nama</th><th>Keterangan</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    @forelse($kategori as $k)
        <tr>
            <td>{{ $k->nama }}</td>
            <td>{{ $k->keterangan ?? '-' }}</td>
            <td><span class="badge {{ $k->aktif ? 'bg-success' : 'bg-secondary' }}">{{ $k->aktif ? 'Aktif' : 'Nonaktif' }}</span></td>
            <td>
                <a href="{{ route('kategori-bencana.edit', $k) }}" class="btn btn-sm btn-warning">Ubah</a>
                <form action="{{ route('kategori-bencana.destroy', $k) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Hapus kategori ini? Jika sudah dipakai, akan dinonaktifkan.')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="4" class="text-center">Belum ada kategori.</td></tr>
    @endforelse
    </tbody>
</table>
{{ $kategori->links() }}
@endsection