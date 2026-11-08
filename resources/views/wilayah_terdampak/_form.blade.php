<form method="POST" action="{{ $formAction }}">
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="kecamatan" class="form-label">Kecamatan <span class="text-danger">*</span></label>
            <select id="kecamatan" name="kecamatan" class="form-select @error('kecamatan') is-invalid @enderror" required>
                <option value="">Pilih kecamatan</option>
                @foreach ($kecamatanList as $kecamatan)
                    <option value="{{ $kecamatan }}" @selected(old('kecamatan', $wilayahTerdampak->kecamatan ?? null) === $kecamatan)>{{ $kecamatan }}</option>
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
                            <option value="{{ $desa }}" @selected(old('desa_kelurahan', $wilayahTerdampak->desa_kelurahan ?? null) === $desa)>{{ $desa }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            <div class="form-text">Pilih desa/kelurahan yang berada di kecamatan terpilih.</div>
            @error('desa_kelurahan') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="tanggal_kejadian" class="form-label">Tanggal Kejadian <span class="text-danger">*</span></label>
            <input id="tanggal_kejadian" name="tanggal_kejadian" type="date" class="form-control @error('tanggal_kejadian') is-invalid @enderror" value="{{ old('tanggal_kejadian', isset($wilayahTerdampak) ? $wilayahTerdampak->tanggal_kejadian?->format('Y-m-d') : null) }}" max="{{ now()->toDateString() }}" required>
            @error('tanggal_kejadian') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="jumlah_kk_terdampak" class="form-label">Jumlah KK Terdampak <span class="text-danger">*</span></label>
            <input id="jumlah_kk_terdampak" name="jumlah_kk_terdampak" type="number" min="1" max="100000" step="1" class="form-control @error('jumlah_kk_terdampak') is-invalid @enderror" value="{{ old('jumlah_kk_terdampak', $wilayahTerdampak->jumlah_kk_terdampak ?? null) }}" required>
            @error('jumlah_kk_terdampak') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="status_penanganan" class="form-label">Status Penanganan <span class="text-danger">*</span></label>
        <select id="status_penanganan" name="status_penanganan" class="form-select @error('status_penanganan') is-invalid @enderror" required>
            <option value="">Pilih status</option>
            @foreach ($statusList as $status)
                <option value="{{ $status }}" @selected(old('status_penanganan', $wilayahTerdampak->status_penanganan ?? null) === $status)>{{ $status }}</option>
            @endforeach
        </select>
        @error('status_penanganan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-4">
        <label for="keterangan" class="form-label">Keterangan (opsional)</label>
        <textarea id="keterangan" name="keterangan" rows="4" maxlength="500" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $wilayahTerdampak->keterangan ?? null) }}</textarea>
        @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-sg">{{ $submitLabel }}</button>
        <a href="{{ route('wilayah-terdampak.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
