@csrf
<div class="mb-3">
    <label for="jenis_fasilitas" class="form-label">Jenis fasilitas <span class="text-danger">*</span></label>
    <input id="jenis_fasilitas" name="jenis_fasilitas" type="text" maxlength="100" list="saran-fasilitas" class="form-control @error('jenis_fasilitas') is-invalid @enderror" value="{{ old('jenis_fasilitas', $fasilitasPosko?->jenis_fasilitas) }}" required>
    <datalist id="saran-fasilitas">@foreach ($jenisSaran as $saran)<option value="{{ $saran }}">@endforeach</datalist>
    @error('jenis_fasilitas')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label for="jumlah" class="form-label">Jumlah <span class="text-danger">*</span></label>
    <input id="jumlah" name="jumlah" type="number" min="1" max="10000" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah', $fasilitasPosko?->jumlah) }}" required>
    @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label for="kondisi" class="form-label">Kondisi <span class="text-danger">*</span></label>
    <select id="kondisi" name="kondisi" class="form-select @error('kondisi') is-invalid @enderror" required>
        <option value="">Pilih kondisi</option>
        @foreach (\App\Models\FasilitasPosko::KONDISI as $kondisi)<option value="{{ $kondisi }}" @selected(old('kondisi', $fasilitasPosko?->kondisi) === $kondisi)>{{ $kondisi }}</option>@endforeach
    </select>
    @error('kondisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label for="keterangan" class="form-label">Keterangan</label>
    <textarea id="keterangan" name="keterangan" maxlength="255" rows="3" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $fasilitasPosko?->keterangan) }}</textarea>
    @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
