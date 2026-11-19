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