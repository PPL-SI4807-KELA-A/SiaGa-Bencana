@extends('layouts.app')
@section('judul', 'Laporan Kejadian')
@section('subjudul', 'Laporan bencana yang dikirim Warga/Pelapor')
@section('menu_aktif', 'kejadian')

@section('content')
<div class="sg-card p-3">
    <table class="table table-striped align-middle mb-0">
        <thead>
            <tr>
                <th>Kode</th><th>Kategori</th><th>Desa/Kelurahan</th>
                <th>Waktu Kejadian</th><th>Status</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($laporan as $l)
            <tr>
                <td>{{ $l->kode_pelacakan }}</td>
                <td>{{ $l->kategori->nama ?? '-' }}</td>
                <td>{{ $l->desa_kelurahan }}</td>
                <td>{{ $l->waktu_kejadian->format('d/m/Y H:i') }}</td>
                <td><span class="badge bg-warning text-dark">{{ $l->status }}</span></td>
                <td>
                    <a href="{{ route('laporan-kejadian.show', $l->id) }}" class="btn btn-sm btn-sg">Lihat</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted">Belum ada laporan.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $laporan->links() }}</div>
@endsection