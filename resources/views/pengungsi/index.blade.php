@extends('layouts.app')

@section('title', 'Data Pengungsi')
@section('judul', 'Data Pengungsi')
@section('subjudul', $posko['nama'])
@section('menu_aktif', 'pengungsi')

@php
    $editId = old('_pengungsi_id');
    $bukaModal = $errors->any() && $editId !== null;
    $modalEdit = $bukaModal && $editId !== 'baru';
    $formAction = $modalEdit ? route('pengungsi.update', $editId) : route('pengungsi.store');
    $warnaBadge = ['Siap' => 'badge-siap', 'Hampir Penuh' => 'badge-hampir', 'Penuh' => 'badge-penuh'][$posko['label']];
    $warnaBar = ['Siap' => '#4F7A3A', 'Hampir Penuh' => '#C98512', 'Penuh' => '#B3412F'][$posko['label']];
    $jumlahBerat = count($beratIds);
@endphp

@push('styles')
<style>
    .peng-page .stat-label { font-size: .6875rem; color: var(--sg-muted); }
    .peng-page .big-num { font-size: 2rem; font-weight: 700; line-height: 1; }
    .peng-page .age-box { background: #FAF3E8; border-radius: 10px; text-align: center; padding: .55rem .5rem; min-width: 74px; }
    .peng-page .age-box strong { display: block; font-size: 1.05rem; }
    .peng-page .berat-card { background: #FBE9E6; border: 1px solid #F1C7C0; color: #8E2F20; border-radius: 10px; padding: .6rem .9rem; }
    .peng-page .progress { height: 8px; background: #EDE3D3; }
    .peng-page .pill { display: inline-flex; align-items: center; gap: .3rem; padding: .2rem .65rem; border-radius: 999px; font-size: .72rem; font-weight: 600; white-space: nowrap; }
    .peng-page .pill::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .pill-aktif { background: #E6EFDD; color: #3F6B2B; }
    .pill-warn { background: #FBEFD5; color: #9A6408; }
    .pill-muted { background: #ECE7E0; color: #6B5F55; }
    .badge-siap { background: #E6EFDD; color: #3F6B2B; }
    .badge-hampir { background: #FBEFD5; color: #9A6408; }
    .badge-penuh { background: #FBE9E6; color: #8E2F20; }
    .peng-page .chip-berat { background: #B3412F; color: #fff; font-size: .65rem; font-weight: 600; padding: .12rem .45rem; border-radius: 999px; margin-left: .35rem; white-space: nowrap; }
    .peng-page .table thead th { font-size: .68rem; letter-spacing: .06em; text-transform: uppercase; color: var(--sg-muted); font-weight: 600; background: #FAF6EE; }
    .peng-page .btn-icon { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--sg-card-border); background: #fff; color: var(--sg-accent); border-radius: 8px; }
    .peng-page .btn-icon:hover { background: #F5EBDD; }
    .peng-page .btn-icon.danger { color: #B3412F; }
    .peng-page .form-control, .peng-page .form-select, .modal .form-control, .modal .form-select { border-color: #E6D8C3; border-radius: 8px; }
    .modal .form-control:focus, .modal .form-select:focus, .peng-page .form-control:focus, .peng-page .form-select:focus { border-color: var(--sg-tan); box-shadow: 0 0 0 .2rem rgba(201, 162, 122, .25); }
    .modal .alert-kapasitas { background: #F9EBD7; border: 1px solid #EBD3A8; color: #8A5A0A; border-radius: 10px; font-size: .8rem; }
    .modal .btn-gender { border: 1px solid #E6D8C3; color: var(--sg-text); font-weight: 600; font-size: .8125rem; border-radius: 8px; }
    .modal .btn-check:checked + .btn-gender { background: var(--sg-accent); border-color: var(--sg-accent); color: #fff; }
    .modal-footer-soft { background: #FAF6EE; border-top: 1px solid var(--sg-card-border); }
</style>
@endpush

@section('content')
<div class="container-fluid px-0 peng-page" style="max-width: 1200px;">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="status">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- RINGKASAN: sisa kapasitas + kelompok usia + kondisi berat (Gambar 4.7) --}}
    <div class="row g-3 mb-3">
        <div class="col-lg-5">
            <div class="sg-card p-3 h-100 d-flex align-items-center gap-3">
                <div class="text-center">
                    <div class="big-num">{{ $posko['sisa'] }}</div>
                    <div class="stat-label">jiwa</div>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold">Sisa kapasitas posko</span>
                        <span class="pill {{ $warnaBadge }}">{{ $posko['label'] }}</span>
                    </div>
                    <div class="progress" role="progressbar" aria-valuenow="{{ $posko['persen'] }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" style="width: {{ min($posko['persen'], 100) }}%; background: {{ $warnaBar }};"></div>
                    </div>
                    <div class="stat-label mt-2">{{ $posko['terisi'] }} dari {{ $posko['kapasitas'] }} jiwa terisi ({{ $posko['persen'] }}%)</div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="sg-card p-3 h-100 d-flex flex-wrap align-items-center gap-2">
                @foreach ($usiaList as $usia)
                    <div class="age-box">
                        <strong>{{ $perUsia[$usia] ?? 0 }}</strong>
                        <span class="stat-label">{{ $usia }}</span>
                    </div>
                @endforeach
                <div class="berat-card flex-grow-1 d-flex align-items-center gap-2">
                    <i class="bi bi-heart-pulse fs-5"></i>
                    <span>
                        @if ($jumlahBerat > 0)
                            <strong>{{ $jumlahBerat }} orang</strong> dengan kondisi medis berat
                        @else
                            Tidak ada kondisi medis berat
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- DAFTAR PENGUNGSI --}}
    <div class="sg-card overflow-hidden">
        <div class="p-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <h2 class="fs-6 fw-bold mb-0">Daftar Pengungsi</h2>
            <form method="GET" action="{{ route('pengungsi.index') }}" class="d-flex flex-wrap gap-2 align-items-center">
                <div class="input-group" style="width: 260px;">
                    <span class="input-group-text bg-white" style="border-color:#E6D8C3;"><i class="bi bi-search"></i></span>
                    <input type="search" name="q" value="{{ $cari }}" class="form-control" placeholder="Cari nama atau alamat" aria-label="Cari nama atau alamat">
                </div>
                <select name="status" class="form-select" style="width: 170px;" aria-label="Filter status" onchange="this.form.submit()">
                    <option value="">Semua status</option>
                    @foreach ($statusList as $s)
                        <option value="{{ $s }}" @selected($filterStatus === $s)>{{ $s }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-sg" data-bs-toggle="modal" data-bs-target="#modalPengungsi">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Pengungsi
                </button>
            </form>
        </div>

        @if ($pengungsi->isEmpty())
            <div class="text-center text-body-secondary py-5 border-top">
                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                {{ ($cari !== '' || $filterStatus !== '') ? 'Tidak ada pengungsi yang sesuai pencarian.' : 'Belum ada data pengungsi.' }}
            </div>
        @else
            <div class="table-responsive border-top">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Kelompok Usia</th>
                            <th>Alamat Asal</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengungsi as $p)
                            <tr>
                                <td class="fw-semibold">
                                    {{ $p->nama }}
                                    @if (in_array($p->id, $beratIds, true))
                                        <span class="chip-berat"><i class="bi bi-heart-pulse-fill me-1"></i>Berat</span>
                                    @endif
                                </td>
                                <td>{{ $p->jenis_kelamin }}</td>
                                <td>{{ $p->kelompok_usia }}</td>
                                <td>{{ $p->alamat_asal }}</td>
                                <td><span class="pill {{ \App\Models\Pengungsi::WARNA_STATUS[$p->status] ?? 'pill-muted' }}">{{ $p->status }}</span></td>
                                <td class="text-end text-nowrap">
                                    <button type="button" class="btn-icon" data-bs-toggle="modal" data-bs-target="#modalPengungsi"
                                            data-update-url="{{ route('pengungsi.update', $p) }}"
                                            data-id="{{ $p->id }}"
                                            data-nama="{{ $p->nama }}"
                                            data-jenis-kelamin="{{ $p->jenis_kelamin }}"
                                            data-kelompok-usia="{{ $p->kelompok_usia }}"
                                            data-nik="{{ $p->nik }}"
                                            data-alamat-asal="{{ $p->alamat_asal }}"
                                            data-status="{{ $p->status }}"
                                            aria-label="Ubah {{ $p->nama }}"><i class="bi bi-pencil"></i></button>
                                    <button type="button" class="btn-icon danger" data-bs-toggle="modal" data-bs-target="#modalHapus"
                                            data-delete-url="{{ route('pengungsi.destroy', $p) }}"
                                            data-nama="{{ $p->nama }}"
                                            aria-label="Hapus {{ $p->nama }}"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="stat-label d-flex align-items-center gap-2">
                    <span>Menampilkan {{ $pengungsi->firstItem() }}–{{ $pengungsi->lastItem() }} dari {{ $pengungsi->total() }} pengungsi</span>
                    <span class="chip-berat ms-2">Berat</span><span>= kondisi medis berat</span>
                </div>
                <div>{{ $pengungsi->links('pagination::bootstrap-5') }}</div>
            </div>
        @endif
    </div>
</div>

{{-- MODAL TAMBAH / UBAH (Gambar 4.8) --}}
<div class="modal fade" id="modalPengungsi" tabindex="-1" aria-labelledby="judulModalPengungsi" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form method="POST" id="formPengungsi" action="{{ $formAction }}" class="modal-content border-0" style="border-radius: 14px; overflow: hidden;">
            @csrf
            <input type="hidden" name="_method" id="inputMethod" value="{{ $modalEdit ? 'PUT' : 'POST' }}">
            <input type="hidden" name="_pengungsi_id" id="inputId" value="{{ $modalEdit ? $editId : 'baru' }}">

            <div class="modal-header">
                <h2 class="modal-title fs-5 fw-bold" id="judulModalPengungsi"><i class="bi bi-person-plus me-2"></i><span id="teksJudul">{{ $modalEdit ? 'Ubah Pengungsi' : 'Tambah Pengungsi' }}</span></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <div class="alert-kapasitas px-3 py-2 mb-3 {{ $errors->has('kapasitas') ? 'border-danger text-danger bg-danger-subtle' : '' }}">
                    <i class="bi bi-house-exclamation me-1"></i>
                    @if ($errors->has('kapasitas'))
                        {{ $errors->first('kapasitas') }}
                    @else
                        Sisa kapasitas {{ $posko['nama'] }}: <strong>{{ $posko['sisa'] }} jiwa</strong>.
                    @endif
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nama" class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                        <input id="nama" name="nama" type="text" maxlength="100" placeholder="Contoh: Siti Aminah" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" required>
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <span class="form-label fw-semibold d-block">Jenis kelamin <span class="text-danger">*</span></span>
                        <div class="d-flex gap-2">
                            @foreach ($jenisKelaminList as $jk)
                                <div class="flex-fill">
                                    <input type="radio" class="btn-check" name="jenis_kelamin" id="jk{{ $loop->index }}" value="{{ $jk }}" @checked(old('jenis_kelamin', 'Laki-laki') === $jk)>
                                    <label class="btn btn-gender w-100" for="jk{{ $loop->index }}">{{ $jk }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('jenis_kelamin') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="kelompok_usia" class="form-label fw-semibold">Kelompok usia <span class="text-danger">*</span></label>
                        <select id="kelompok_usia" name="kelompok_usia" class="form-select @error('kelompok_usia') is-invalid @enderror" required>
                            <option value="">Pilih: Balita, Anak, Dewasa, Lansia</option>
                            @foreach ($usiaList as $usia)
                                <option value="{{ $usia }}" @selected(old('kelompok_usia') === $usia)>{{ $usia }}</option>
                            @endforeach
                        </select>
                        @error('kelompok_usia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="nik" class="form-label fw-semibold">NIK <span class="fw-normal text-body-secondary">(opsional)</span></label>
                        <input id="nik" name="nik" type="text" inputmode="numeric" maxlength="16" placeholder="16 digit" value="{{ old('nik') }}" class="form-control @error('nik') is-invalid @enderror">
                        @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="form-text">Boleh dikosongkan jika dokumen hilang atau terendam.</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="alamat_asal" class="form-label fw-semibold">Alamat asal <span class="text-danger">*</span></label>
                        <textarea id="alamat_asal" name="alamat_asal" rows="3" maxlength="255" placeholder="Kampung, RT/RW, desa" class="form-control @error('alamat_asal') is-invalid @enderror" required>{{ old('alamat_asal') }}</textarea>
                        @error('alamat_asal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                            @foreach ($statusList as $s)
                                <option value="{{ $s }}" @selected(old('status', 'Aktif') === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="form-text">Aktif, Pindah Posko, Kembali ke Rumah, atau Dirujuk.</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="modal-footer modal-footer-soft">
                <button type="button" class="btn btn-outline-secondary" style="border-color:#E6D8C3;color:var(--sg-accent);background:#fff;" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sg"><i class="bi bi-check2 me-1"></i>Simpan Pengungsi</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL HAPUS --}}
<div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="judulModalHapus" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="judulModalHapus">Hapus Data Pengungsi</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                Hapus data <strong id="namaHapus"></strong>? Gunakan hanya untuk data yang salah input. Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <form method="POST" id="formHapus" action="#">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const modalEl = document.getElementById('modalPengungsi');
        const form = document.getElementById('formPengungsi');
        const storeUrl = @json(route('pengungsi.store'));
        let bukaKarenaError = @json($bukaModal);

        function isi(d) {
            form.nama.value = d.nama || '';
            form.nik.value = d.nik || '';
            form.alamat_asal.value = d.alamat_asal || '';
            form.kelompok_usia.value = d.kelompok_usia || '';
            form.status.value = d.status || 'Aktif';
            const jk = d.jenis_kelamin || 'Laki-laki';
            form.querySelectorAll('input[name="jenis_kelamin"]').forEach(r => r.checked = (r.value === jk));
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        }

        modalEl.addEventListener('show.bs.modal', function (e) {
            if (bukaKarenaError) { bukaKarenaError = false; return; } // pertahankan isian + pesan error
            const btn = e.relatedTarget;
            const edit = btn && btn.dataset.updateUrl;
            document.getElementById('teksJudul').textContent = edit ? 'Ubah Pengungsi' : 'Tambah Pengungsi';
            document.getElementById('inputMethod').value = edit ? 'PUT' : 'POST';
            document.getElementById('inputId').value = edit ? btn.dataset.id : 'baru';
            form.action = edit ? btn.dataset.updateUrl : storeUrl;
            isi(edit ? {
                nama: btn.dataset.nama, nik: btn.dataset.nik, alamat_asal: btn.dataset.alamatAsal,
                kelompok_usia: btn.dataset.kelompokUsia, status: btn.dataset.status, jenis_kelamin: btn.dataset.jenisKelamin,
            } : {});
        });

        document.getElementById('modalHapus').addEventListener('show.bs.modal', function (e) {
            document.getElementById('formHapus').action = e.relatedTarget.dataset.deleteUrl;
            document.getElementById('namaHapus').textContent = e.relatedTarget.dataset.nama;
        });

        if (bukaKarenaError) {
            new bootstrap.Modal(modalEl).show();
        }
    })();
</script>
@endpush
