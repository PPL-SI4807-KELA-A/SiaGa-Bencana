<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stok Logistik Posko - SiaGa Bencana</title>
    {{-- Sementara mandiri. Setelah layout Arkan masuk ke main, ganti dengan @extends. --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Stok Logistik Posko</h4>
        @if ($isRelawan)
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                + Tambah Barang ke Stok
            </button>
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any() && old('_form') !== 'tambah')
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($jumlahMenipis > 0)
        <div class="alert alert-warning">
            <strong>{{ $jumlahMenipis }} barang</strong> sama dengan atau di bawah batas minimum.
        </div>
    @endif

    <form method="GET" action="{{ route('stok-posko.index') }}" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                   placeholder="Cari nama atau kode barang">
        </div>
        @unless ($isRelawan)
            <div class="col-md-3">
                <select name="posko_id" class="form-select">
                    <option value="">Semua posko</option>
                    @foreach ($daftarPosko as $p)
                        <option value="{{ $p->posko_id }}" @selected(request('posko_id') == $p->posko_id)>
                            {{ $p->nama_posko }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endunless
        <div class="col-md-3 d-flex align-items-center">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="menipis" value="1" id="menipis"
                       @checked(request('menipis'))>
                <label class="form-check-label" for="menipis">Hanya yang menipis</label>
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-outline-secondary w-100">Terapkan</button>
        </div>
    </form>

    <div class="table-responsive bg-white rounded border">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Barang</th>
                    @unless ($isRelawan) <th>Posko</th> @endunless
                    <th class="text-end">Stok</th>
                    <th class="text-end">Batas Min.</th>
                    <th>Status</th>
                    @if ($isRelawan) <th style="width: 110px">Aksi</th> @endif
                </tr>
            </thead>
            <tbody>
            @forelse ($items as $item)
                <tr class="{{ $item->isMenipis() ? 'table-danger' : '' }}">
                    <td>
                        <div class="fw-semibold">{{ $item->nama_barang }}</div>
                        <div class="small text-muted">{{ $item->kode_barang }} &middot; {{ ucfirst($item->kategori) }}</div>
                    </td>
                    @unless ($isRelawan) <td>{{ $item->nama_posko }}</td> @endunless
                    <td class="text-end fw-semibold">{{ number_format($item->jumlah_stok) }} {{ $item->satuan }}</td>
                    <td class="text-end">
                        @if ($isRelawan)
                            <form method="POST" action="{{ route('stok-posko.update', $item) }}"
                                  class="d-inline-flex gap-1 justify-content-end">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="batas_minimum" min="0" value="{{ $item->batas_minimum }}"
                                       class="form-control form-control-sm" style="width: 100px"
                                       aria-label="Batas minimum {{ $item->nama_barang }}">
                                <button type="submit" class="btn btn-sm btn-outline-secondary">Simpan</button>
                            </form>
                        @else
                            {{ number_format($item->batas_minimum) }} {{ $item->satuan }}
                        @endif
                    </td>
                    <td>
                        @if ($item->isMenipis())
                            <span class="badge text-bg-danger">Di bawah minimum</span>
                        @else
                            <span class="badge text-bg-success">Aman</span>
                        @endif
                    </td>
                    @if ($isRelawan)
                        <td>
                            @if ($item->memiliki_transaksi)
                                <button type="button" class="btn btn-sm btn-outline-danger" disabled
                                        title="Sudah memiliki transaksi">Hapus</button>
                            @else
                                <form method="POST" action="{{ route('stok-posko.destroy', $item) }}"
                                      onsubmit="return confirm('Hapus {{ e($item->nama_barang) }} dari daftar stok?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            @endif
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        Belum ada data stok.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $items->links('pagination::bootstrap-5') }}</div>
</div>

@if ($isRelawan)
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('stok-posko.store') }}" class="modal-content">
            @csrf
            <input type="hidden" name="_form" value="tambah">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahLabel">Tambah Barang ke Stok</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                @if ($errors->any() && old('_form') === 'tambah')
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label" for="kode_barang">Kode barang <span class="text-danger">*</span></label>
                        <input type="text" id="kode_barang" name="kode_barang" value="{{ old('kode_barang') }}"
                               class="form-control @error('kode_barang') is-invalid @enderror" required>
                    </div>
                    <div class="col-md-7">
                        <label class="form-label" for="nama_barang">Nama barang <span class="text-danger">*</span></label>
                        <input type="text" id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}"
                               class="form-control @error('nama_barang') is-invalid @enderror" required>
                    </div>
                    <div class="col-md-7">
                        <label class="form-label" for="kategori">Kategori <span class="text-danger">*</span></label>
                        <select id="kategori" name="kategori"
                                class="form-select @error('kategori') is-invalid @enderror" required>
                            <option value="">Pilih kategori</option>
                            @foreach ($kategori as $k)
                                <option value="{{ $k }}" @selected(old('kategori') === $k)>{{ ucfirst($k) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label" for="satuan">Satuan <span class="text-danger">*</span></label>
                        <input type="text" id="satuan" name="satuan" value="{{ old('satuan') }}"
                               class="form-control @error('satuan') is-invalid @enderror" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="jumlah_stok">Jumlah stok awal <span class="text-danger">*</span></label>
                        <input type="number" min="0" id="jumlah_stok" name="jumlah_stok" value="{{ old('jumlah_stok', 0) }}"
                               class="form-control @error('jumlah_stok') is-invalid @enderror" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="batas_minimum_baru">Batas minimum <span class="text-danger">*</span></label>
                        <input type="number" min="0" id="batas_minimum_baru" name="batas_minimum" value="{{ old('batas_minimum', 0) }}"
                               class="form-control @error('batas_minimum') is-invalid @enderror" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@if ($isRelawan && $errors->any() && old('_form') === 'tambah')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalEl = document.getElementById('modalTambah');
            if (modalEl && window.bootstrap) {
                window.bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }
        });
    </script>
@endif
</body>
</html>