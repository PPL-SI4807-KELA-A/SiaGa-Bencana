<form method="POST" action="{{ $formAction }}">
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <div class="mb-3">
        <label for="jenis_kebutuhan" class="form-label">Jenis Kebutuhan <span class="text-danger">*</span></label>
        <input id="jenis_kebutuhan" name="jenis_kebutuhan" type="text" maxlength="100" value="{{ old('jenis_kebutuhan', $permintaan->jenis_kebutuhan ?? '') }}" class="form-control @error('jenis_kebutuhan') is-invalid @enderror" required>
        @error('jenis_kebutuhan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="perkiraan_jumlah" class="form-label">Perkiraan Jumlah <span class="text-danger">*</span></label>
        <input id="perkiraan_jumlah" name="perkiraan_jumlah" type="number" min="1" step="1" value="{{ old('perkiraan_jumlah', $permintaan->perkiraan_jumlah ?? '') }}" class="form-control @error('perkiraan_jumlah') is-invalid @enderror" required>
        @error('perkiraan_jumlah') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="lokasi" class="form-label">Lokasi <span class="text-danger">*</span></label>
        <input id="lokasi" name="lokasi" type="text" maxlength="255" value="{{ old('lokasi', $permintaan->lokasi ?? '') }}" class="form-control @error('lokasi') is-invalid @enderror" required>
        @error('lokasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="nomor_kontak" class="form-label">Nomor Kontak <span class="text-danger">*</span></label>
        <input id="nomor_kontak" name="nomor_kontak" type="tel" inputmode="tel" maxlength="16" pattern="\+?[0-9]{8,15}" value="{{ old('nomor_kontak', $permintaan->nomor_kontak ?? '') }}" class="form-control @error('nomor_kontak') is-invalid @enderror" required>
        <div class="form-text">8 sampai 15 digit; tanda + boleh digunakan di awal.</div>
        @error('nomor_kontak') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-4">
        <label for="posko" class="form-label">Posko <span class="text-body-secondary">(opsional)</span></label>
        <select id="posko" name="posko" class="form-select @error('posko') is-invalid @enderror">
            <option value="">Tidak memilih posko</option>
            @foreach ($daftarPosko as $namaPosko)
                <option value="{{ $namaPosko }}" @selected(old('posko', $permintaan->posko ?? '') === $namaPosko)>{{ $namaPosko }}</option>
            @endforeach
        </select>
        @error('posko') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="d-flex flex-wrap gap-2">
        <button type="submit" class="btn btn-sg">{{ $submitLabel }}</button>
        <a href="{{ route('permintaan-mendesak.lacak') }}" class="btn btn-outline-secondary">Lacak Permintaan</a>
    </div>
</form>
