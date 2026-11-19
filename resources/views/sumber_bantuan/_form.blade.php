<form method="POST" action="{{ $formAction }}">
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <div class="mb-3">
        <label for="nama" class="form-label">Nama Sumber Bantuan <span class="text-danger">*</span></label>
        <input id="nama" name="nama" type="text" maxlength="255" value="{{ old('nama', $sumberBantuan->nama ?? '') }}" class="form-control @error('nama') is-invalid @enderror" required>
        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="jenis" class="form-label">Jenis <span class="text-danger">*</span></label>
        <select id="jenis" name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
            <option value="">Pilih jenis</option>
            @foreach ($jenisList as $jenis)
                <option value="{{ $jenis }}" @selected(old('jenis', $sumberBantuan->jenis ?? '') === $jenis)>{{ ucfirst($jenis) }}</option>
            @endforeach
        </select>
        @error('jenis') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="kontak" class="form-label">Kontak <span class="text-danger">*</span></label>
        <input id="kontak" name="kontak" type="text" maxlength="255" value="{{ old('kontak', $sumberBantuan->kontak ?? '') }}" class="form-control @error('kontak') is-invalid @enderror" required>
        @error('kontak') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    @isset($sumberBantuan)
        <div class="mb-4">
            <label for="aktif" class="form-label">Status <span class="text-danger">*</span></label>
            <select id="aktif" name="aktif" class="form-select @error('aktif') is-invalid @enderror" required>
                <option value="1" @selected((string) old('aktif', (int) $sumberBantuan->aktif) === '1')>Aktif</option>
                <option value="0" @selected((string) old('aktif', (int) $sumberBantuan->aktif) === '0')>Nonaktif</option>
            </select>
            @error('aktif') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    @endisset

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-sg">{{ $submitLabel }}</button>
        <a href="{{ route('sumber-bantuan.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>