<form method="POST" action="{{ $formAction }}">
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <div class="mb-3">
        <label for="nama" class="form-label">Nama Barang <span class="text-danger">*</span></label>
        <input id="nama" name="nama" type="text" maxlength="255" value="{{ old('nama', $barang->nama ?? '') }}" class="form-control @error('nama') is-invalid @enderror" required>
        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
        <select id="kategori" name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
            <option value="">Pilih kategori</option>
            @foreach ($kategoriList as $kategori)
                <option value="{{ $kategori }}" @selected(old('kategori', $barang->kategori ?? '') === $kategori)>{{ ucfirst($kategori) }}</option>
            @endforeach
        </select>
        @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="satuan" class="form-label">Satuan <span class="text-danger">*</span></label>
        <input id="satuan" name="satuan" type="text" maxlength="50" value="{{ old('satuan', $barang->satuan ?? '') }}" class="form-control @error('satuan') is-invalid @enderror" required>
        @error('satuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    @isset($barang)
        <div class="mb-4">
            <label for="aktif" class="form-label">Status <span class="text-danger">*</span></label>
            <select id="aktif" name="aktif" class="form-select @error('aktif') is-invalid @enderror" required>
                <option value="1" @selected((string) old('aktif', (int) $barang->aktif) === '1')>Aktif</option>
                <option value="0" @selected((string) old('aktif', (int) $barang->aktif) === '0')>Nonaktif</option>
            </select>
            @error('aktif') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    @endisset

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-sg">{{ $submitLabel }}</button>
        <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
