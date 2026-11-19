<form method="POST" action="{{ $formAction }}">
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <div class="mb-3">
        <label for="nama_posko" class="form-label">Nama Posko <span class="text-danger">*</span></label>
        <input id="nama_posko" name="nama_posko" type="text" maxlength="150" class="form-control @error('nama_posko') is-invalid @enderror" value="{{ old('nama_posko', $posko->nama_posko ?? null) }}" required>
        @error('nama_posko') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
        <textarea id="alamat" name="alamat" rows="3" maxlength="255" class="form-control @error('alamat') is-invalid @enderror" required>{{ old('alamat', $posko->alamat ?? null) }}</textarea>
        @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="kecamatan" class="form-label">Kecamatan <span class="text-danger">*</span></label>
            <select id="kecamatan" name="kecamatan" class="form-select @error('kecamatan') is-invalid @enderror" required>
                <option value="">Pilih kecamatan</option>
                @foreach ($kecamatanList as $kecamatan)
                    <option value="{{ $kecamatan }}" @selected(old('kecamatan', $posko->kecamatan ?? null) === $kecamatan)>{{ $kecamatan }}</option>
                @endforeach
            </select>
            @error('kecamatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="desa_kelurahan" class="form-label">Desa/Kelurahan <span class="text-danger">*</span></label>
            <select id="desa_kelurahan" name="desa_kelurahan" class="form-select @error('desa_kelurahan') is-invalid @enderror" required>
                <option value="">Pilih desa/kelurahan</option>
                @foreach ($desaPerKecamatan as $namaKecamatan => $daftarDesa)
                    <optgroup label="Kecamatan {{ $namaKecamatan }}">
                        @foreach ($daftarDesa as $desa)
                            <option value="{{ $desa }}" @selected(old('desa_kelurahan', $posko->desa_kelurahan ?? null) === $desa)>{{ $desa }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            @error('desa_kelurahan') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="penanggung_jawab" class="form-label">Penanggung Jawab <span class="text-danger">*</span></label>
            <input id="penanggung_jawab" name="penanggung_jawab" type="text" maxlength="100" class="form-control @error('penanggung_jawab') is-invalid @enderror" value="{{ old('penanggung_jawab', $posko->penanggung_jawab ?? null) }}" required>
            @error('penanggung_jawab') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="kontak_penanggung_jawab" class="form-label">Kontak Penanggung Jawab (opsional)</label>
            <input id="kontak_penanggung_jawab" name="kontak_penanggung_jawab" type="tel" maxlength="20" class="form-control @error('kontak_penanggung_jawab') is-invalid @enderror" value="{{ old('kontak_penanggung_jawab', $posko->kontak_penanggung_jawab ?? null) }}" placeholder="08xxxxxxxxxx">
            @error('kontak_penanggung_jawab') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <label for="kapasitas" class="form-label">Kapasitas <span class="text-danger">*</span></label>
            <input id="kapasitas" name="kapasitas" type="number" min="1" max="10000" step="1" class="form-control @error('kapasitas') is-invalid @enderror" value="{{ old('kapasitas', $posko->kapasitas ?? null) }}" required>
            @error('kapasitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-4">
            <label for="jumlah_terisi" class="form-label">Jumlah Terisi <span class="text-danger">*</span></label>
            <input id="jumlah_terisi" name="jumlah_terisi" type="number" min="0" max="10000" step="1" class="form-control @error('jumlah_terisi') is-invalid @enderror" value="{{ old('jumlah_terisi', $posko->jumlah_terisi ?? 0) }}" required>
            @error('jumlah_terisi') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-sg">{{ $submitLabel }}</button>
        <a href="{{ route('posko.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
