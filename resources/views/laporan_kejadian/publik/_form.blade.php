@php $l = $laporan ?? null; @endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Kategori bencana <span class="text-danger">*</span></label>
        <select name="kategori_bencana_id" class="form-select @error('kategori_bencana_id') is-invalid @enderror">
            <option value="">Pilih kategori</option>
            @foreach($kategori as $k)
                <option value="{{ $k->id }}" @selected(old('kategori_bencana_id', $l->kategori_bencana_id ?? '') == $k->id)>
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

    <div class="col-md-6 mb-3">
        <label class="form-label">Perkiraan ketinggian air</label>
        <input type="text" name="ketinggian_air"
               class="form-control @error('ketinggian_air') is-invalid @enderror"
               placeholder="Contoh: 50-100 cm"
               value="{{ old('ketinggian_air', $l->ketinggian_air ?? '') }}">
        @error('ketinggian_air')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Lokasi <span class="text-danger">*</span></label>
    <input type="text" name="lokasi" maxlength="255" required
           class="form-control @error('lokasi') is-invalid @enderror"
           placeholder="Nama jalan, RT/RW, atau patokan"
           value="{{ old('lokasi', $l->lokasi ?? '') }}">
    @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Deskripsi kondisi <span class="text-danger">*</span></label>
    <textarea name="deskripsi" rows="4" required
              class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $l->deskripsi ?? '') }}</textarea>
    @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nama pelapor</label>
        <input type="text" name="nama_pelapor" maxlength="100"
               class="form-control @error('nama_pelapor') is-invalid @enderror"
               value="{{ old('nama_pelapor', $l->nama_pelapor ?? '') }}">
        @error('nama_pelapor')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Kontak pelapor <span class="text-danger">*</span></label>
        <input type="text" name="kontak_pelapor" maxlength="20" required
               class="form-control @error('kontak_pelapor') is-invalid @enderror"
               placeholder="Nomor HP/WhatsApp"
               value="{{ old('kontak_pelapor', $l->kontak_pelapor ?? '') }}">
        @error('kontak_pelapor')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Foto kejadian</label>
    <input type="file" name="foto" accept=".jpg,.jpeg,.png"
           class="form-control @error('foto') is-invalid @enderror">
    <div class="form-text">JPG/PNG, maks 2 MB</div>
    @if($l?->foto)
        <div class="form-text">Foto lama tetap dipakai jika tidak diganti.</div>
    @endif
    @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
