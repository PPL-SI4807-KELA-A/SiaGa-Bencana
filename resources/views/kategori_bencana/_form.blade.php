<div class="mb-3">
    <label class="form-label">Nama <span class="text-danger">*</span></label>
    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
           value="{{ old('nama', $kategori->nama ?? '') }}" placeholder="Contoh: Banjir">
    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label">Keterangan</label>
    <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $kategori->keterangan ?? '') }}</textarea>
    @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="form-check mb-3">
    <input type="hidden" name="aktif" value="0">
    <input type="checkbox" name="aktif" value="1" class="form-check-input" id="aktif"
           {{ old('aktif', $kategori->aktif ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="aktif">Aktif (muncul di formulir laporan)</label>
</div>