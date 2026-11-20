@extends('layouts.app')

@section('title', 'Permintaan Mendesak')
@section('judul', 'Permintaan Mendesak')
@section('subjudul', 'Daftar seluruh permintaan kebutuhan mendesak')

@section('content')
<div class="container-fluid px-0" style="max-width: 1200px;">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="status">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="sg-card overflow-hidden">
        @if ($permintaanMendesak->isEmpty())
            <div class="text-center text-body-secondary py-5">
                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                Belum ada permintaan kebutuhan mendesak.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Pelacakan</th>
                            <th>Jenis Kebutuhan</th>
                            <th>Perkiraan Jumlah</th>
                            <th>Lokasi</th>
                            <th>Posko</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permintaanMendesak as $permintaan)
                            <tr>
                                <td class="fw-semibold">{{ $permintaan->kode_pelacakan }}</td>
                                <td>{{ $permintaan->jenis_kebutuhan }}</td>
                                <td>{{ number_format($permintaan->perkiraan_jumlah, 0, ',', '.') }}</td>
                                <td>{{ $permintaan->lokasi }}</td>
                                <td>{{ $permintaan->posko ?? '-' }}</td>
                                <td><span class="badge bg-warning text-dark">{{ $permintaan->status }}</span></td>
                                <td class="text-end text-nowrap">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('permintaan-mendesak.show', $permintaan) }}" aria-label="Detail {{ $permintaan->kode_pelacakan }}">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if ($permintaan->status === \App\Models\PermintaanMendesak::STATUS_DIAJUKAN)
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('permintaan-mendesak.edit', $permintaan) }}" aria-label="Ubah {{ $permintaan->kode_pelacakan }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('permintaan-mendesak.destroy', $permintaan) }}" class="d-inline" onsubmit="return confirm('Batalkan permintaan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit" aria-label="Batalkan {{ $permintaan->kode_pelacakan }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mt-3">{{ $permintaanMendesak->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
