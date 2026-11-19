@extends('layouts.guest')

@section('title', 'Rincian Permintaan')

@section('content')
<div class="container-fluid px-0" style="max-width: 820px;">
    @if (session('success'))
        <div class="alert alert-success" role="status">{{ session('success') }}</div>
    @endif

    @if (session('kode_pelacakan'))
        <div class="alert alert-info" role="status">
            Simpan kode pelacakan ini untuk mengakses permintaan kembali:
            <strong class="d-block fs-5 mt-1">{{ session('kode_pelacakan') }}</strong>
        </div>
    @endif

    <div class="sg-card p-4">
        <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h1 class="h4 mb-1">Rincian Permintaan</h1>
                <p class="text-body-secondary mb-0">Kode pelacakan: <strong>{{ $permintaan->kode_pelacakan }}</strong></p>
            </div>
            <span class="badge bg-warning text-dark">{{ $permintaan->status }}</span>
        </div>

        <dl class="row mb-4">
            <dt class="col-sm-4">Jenis Kebutuhan</dt>
            <dd class="col-sm-8">{{ $permintaan->jenis_kebutuhan }}</dd>
            <dt class="col-sm-4">Perkiraan Jumlah</dt>
            <dd class="col-sm-8">{{ number_format($permintaan->perkiraan_jumlah, 0, ',', '.') }}</dd>
            <dt class="col-sm-4">Lokasi</dt>
            <dd class="col-sm-8">{{ $permintaan->lokasi }}</dd>
            <dt class="col-sm-4">Nomor Kontak</dt>
            <dd class="col-sm-8">{{ $permintaan->nomor_kontak }}</dd>
            <dt class="col-sm-4">Posko</dt>
            <dd class="col-sm-8">{{ $permintaan->posko ?? '-' }}</dd>
        </dl>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('permintaan-mendesak.lacak') }}" class="btn btn-outline-secondary">Lacak Permintaan Lain</a>
            @if ($permintaan->status === \App\Models\PermintaanMendesak::STATUS_DIAJUKAN)
                <a href="{{ route('permintaan-mendesak.edit', $permintaan) }}" class="btn btn-sg">Ubah</a>
                <form method="POST" action="{{ route('permintaan-mendesak.destroy', $permintaan) }}" onsubmit="return confirm('Batalkan permintaan ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">Batalkan Permintaan</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
