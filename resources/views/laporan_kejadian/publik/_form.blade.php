@php
    $l = $laporan ?? null;
    $pilihan = old('kategori_bencana_id', $l->kategori_bencana_id ?? null);
    $awalBanjir = $kategori->firstWhere('id', $pilihan)?->isBanjir() ?? false;
@endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Kategori bencana <span class="text-danger">*</span></label>
        <select name="kategori_bencana_id" id="kategori_bencana_id"
                class="form-select @error('kategori_bencana_id') is-invalid @enderror">
            <option value="">Pilih kategori</option>
            @foreach($kategori as $k)
                <option value="{{ $k->id }}" data-banjir="{{ $k->isBanjir() ? '1' : '0' }}"
                        @selected($pilihan == $k->id)>
                    {{ $k->nama }}
                </option>
            @endforeach
        </select>
        @error('kategori_bencana_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Waktu kejadian <span class="text-danger">*</span></label>
        <input type="datetime-local" name="waktu_kejadian"
               class="form-control @error('waktu_kejadian') is-invalid @enderror"
               value="{{ old('waktu_kejadian', optional($l?->waktu_kejadian)->format('Y-m-d\TH:i')) }}">
        @error('waktu_kejadian')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Desa/kelurahan <span class="text-danger">*</span></label>
        <input type="text" name="desa_kelurahan"
               class="form-control @error('desa_kelurahan') is-invalid @enderror"
               value="{{ old('desa_kelurahan', $l->desa_kelurahan ?? '') }}">
        @error('desa_kelurahan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3" id="wrap-ketinggian" style="{{ $awalBanjir ? '' : 'display:none' }}">
        <label class="form-label">Perkiraan ketinggian air</label>
        <input type="text" name="ketinggian_air" id="ketinggian_air"
               class="form-control @error('ketinggian_air') is-invalid @enderror"
               placeholder="Contoh: 50-100 cm"
               value="{{ old('ketinggian_air', $l->ketinggian_air ?? '') }}">
        @error('ketinggian_air')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Lokasi <span class="text-danger">*</span></label>
    <input type="text" name="lokasi"
           class="form-control @error('lokasi') is-invalid @enderror"
           placeholder="Kp. Cijagra RT 03/RW 07, belakang masjid"
           value="{{ old('lokasi', $l->lokasi ?? '') }}">
    @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Deskripsi kondisi <span class="text-danger">*</span></label>
    <textarea name="deskripsi" rows="4"
              class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $l->deskripsi ?? '') }}</textarea>
    @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Foto kejadian (opsional, JPG/PNG maksimal 2 MB)</label>
    <input type="file" name="foto" accept=".jpg,.jpeg,.png"
           class="form-control @error('foto') is-invalid @enderror">
    @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
    @if($l?->foto)
        <small class="text-muted">Foto saat ini tersimpan. Pilih file baru untuk menggantinya.</small>
    @endif
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nama pelapor</label>
        <input type="text" name="nama_pelapor"
               class="form-control @error('nama_pelapor') is-invalid @enderror"
               value="{{ old('nama_pelapor', $l->nama_pelapor ?? '') }}">
        @error('nama_pelapor')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Kontak pelapor (nomor HP) <span class="text-danger">*</span></label>
        <input type="text" name="kontak_pelapor" inputmode="numeric"
               pattern="[0-9]{8,15}" minlength="8" maxlength="15"
               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
               placeholder="Contoh: 081220457781"
               class="form-control @error('kontak_pelapor') is-invalid @enderror"
               value="{{ old('kontak_pelapor', $l->kontak_pelapor ?? '') }}">
        <small class="text-muted">Hanya angka, 8 sampai 15 digit.</small>
        @error('kontak_pelapor')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
</div>

<script>
    (function () {
        var pilih = document.getElementById('kategori_bencana_id');
        var wrap = document.getElementById('wrap-ketinggian');
        var isian = document.getElementById('ketinggian_air');

        function ubah() {
            var opsi = pilih.options[pilih.selectedIndex];
            var banjir = opsi && opsi.dataset.banjir === '1';
            wrap.style.display = banjir ? '' : 'none';
            if (!banjir) { isian.value = ''; }
        }

        pilih.addEventListener('change', ubah);
        ubah();
    })();
</script>