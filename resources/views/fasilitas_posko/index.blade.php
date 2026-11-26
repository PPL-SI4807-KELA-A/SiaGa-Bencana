@extends('layouts.app')

@section('title', 'Fasilitas Posko')
@section('judul', 'Fasilitas Posko')
@section('subjudul', $admin ? 'Pantau fasilitas di seluruh posko' : 'Kelola fasilitas di posko Anda')
@section('menu_aktif', 'fasilitas')

@section('content')
<div class="container-fluid px-0">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="status">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button></div>
    @endif
    @if (session('error'))<div class="alert alert-danger" role="alert">{{ session('error') }}</div>@endif

    <div class="row g-3 mb-3">
        <div class="col-md-6"><div class="sg-card p-3"><div class="text-body-secondary small">Total jenis fasilitas</div><div class="fs-3 fw-bold">{{ $jumlahJenis }}</div></div></div>
        <div class="col-md-6"><div class="sg-card p-3"><div class="text-body-secondary small">Fasilitas rusak berat</div><div class="fs-3 fw-bold text-danger">{{ $jumlahRusakBerat }}</div></div></div>
    </div>

    <div class="sg-card overflow-hidden">
        <div class="p-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <h2 class="fs-6 fw-bold mb-0">Daftar Fasilitas</h2>
            <form method="GET" action="{{ route('fasilitas-posko.index') }}" class="d-flex flex-wrap gap-2">
                @if ($admin)
                    <select name="posko_id" class="form-select" aria-label="Filter posko" onchange="this.form.submit()">
                        <option value="">Semua posko</option>
                        @foreach ($daftarPosko as $posko)
                            <option value="{{ $posko->id }}" @selected((string) $filterPosko === (string) $posko->id)>{{ $posko->nama_posko }}</option>
                        @endforeach
                    </select>
                @endif
                <select name="kondisi" class="form-select" aria-label="Filter kondisi" onchange="this.form.submit()">
                    <option value="">Semua kondisi</option>
                    @foreach ($kondisiList as $kondisi)<option value="{{ $kondisi }}" @selected($filterKondisi === $kondisi)>{{ $kondisi }}</option>@endforeach
                </select>
                @unless ($admin)<a href="{{ route('fasilitas-posko.create') }}" class="btn btn-sg"><i class="bi bi-plus-lg me-1"></i>Tambah Fasilitas</a>@endunless
            </form>
        </div>
        @if ($fasilitas->isEmpty())
            <div class="text-center text-body-secondary py-5 border-top"><i class="bi bi-inbox fs-2 d-block mb-2"></i>Belum ada data fasilitas.</div>
        @else
            <div class="table-responsive border-top">
                <table class="table table-hover align-middle mb-0">
                    <thead><tr><th>Jenis Fasilitas</th>@if ($admin)<th>Posko</th>@endif<th>Jumlah</th><th>Kondisi</th><th>Keterangan</th>@unless ($admin)<th class="text-end">Aksi</th>@endunless</tr></thead>
                    <tbody>
                    @foreach ($fasilitas as $item)
                        <tr @class(['table-danger' => $item->kondisi === 'Rusak Berat'])>
                            <td class="fw-semibold">{{ $item->jenis_fasilitas }}</td>
                            @if ($admin)<td>{{ $item->nama_posko ?? 'Posko #'.$item->posko_id }}</td>@endif
                            <td>{{ $item->jumlah }}</td>
                            <td><span class="badge {{ \App\Models\FasilitasPosko::WARNA_KONDISI[$item->kondisi] ?? 'bg-secondary' }}">{{ $item->kondisi }}</span></td>
                            <td>{{ $item->keterangan ?: '—' }}</td>
                            @unless ($admin)
                                <td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-primary" href="{{ route('fasilitas-posko.edit', $item) }}">Ubah</a> <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#modalHapus" data-url="{{ route('fasilitas-posko.destroy', $item) }}" data-nama="{{ $item->jenis_fasilitas }}">Hapus</button></td>
                            @endunless
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $fasilitas->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
</div>

@unless ($admin)
<div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="judulModalHapus" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><h2 class="modal-title fs-5" id="judulModalHapus">Hapus Fasilitas</h2><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
        <div class="modal-body">Hapus data fasilitas <strong id="namaHapus"></strong>?</div>
        <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button><form method="POST" id="formHapus" action="#">@csrf @method('DELETE')<button type="submit" class="btn btn-danger">Ya, Hapus</button></form></div>
    </div></div>
</div>
@push('scripts')
<script>
document.getElementById('modalHapus').addEventListener('show.bs.modal', function (event) {
    document.getElementById('formHapus').action = event.relatedTarget.dataset.url;
    document.getElementById('namaHapus').textContent = event.relatedTarget.dataset.nama;
});
</script>
@endpush
@endunless
@endsection
