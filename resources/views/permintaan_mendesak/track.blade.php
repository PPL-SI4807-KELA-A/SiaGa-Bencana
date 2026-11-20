@extends('layouts.guest')

@section('title', 'Lacak Permintaan')

@section('content')
<div class="container-fluid px-0" style="max-width: 640px;">
    @if (session('success'))
        <div class="alert alert-success" role="status">{{ session('success') }}</div>
    @endif

    <div class="sg-card p-4">
        <h1 class="h4 mb-2">Lacak Permintaan</h1>
        <p class="text-body-secondary mb-4">Masukkan kode pelacakan untuk melihat, mengubah, atau membatalkan permintaan.</p>

        <form method="POST" action="{{ route('permintaan-mendesak.proses-lacak') }}">
            @csrf
            <div class="mb-3">
                <label for="kode_pelacakan" class="form-label">Kode Pelacakan</label>
                <input id="kode_pelacakan" name="kode_pelacakan" type="text" maxlength="12" pattern="PMD-[A-Z0-9]{8}" value="{{ old('kode_pelacakan') }}" class="form-control @error('kode_pelacakan') is-invalid @enderror" placeholder="PMD-XXXXXXXX" required>
                @error('kode_pelacakan') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="btn btn-sg">Cari Permintaan</button>
        </form>
    </div>
</div>
@endsection
