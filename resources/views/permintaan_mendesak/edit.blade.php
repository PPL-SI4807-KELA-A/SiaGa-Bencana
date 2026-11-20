@extends('layouts.guest')

@section('title', 'Ubah Permintaan')

@section('content')
<div class="container-fluid px-0" style="max-width: 820px;">
    <div class="sg-card p-4">
        <h1 class="h4 mb-2">Ubah Permintaan</h1>
        <p class="text-body-secondary mb-4">Kode pelacakan: <strong>{{ $permintaan->kode_pelacakan }}</strong></p>

        @include('permintaan_mendesak._form', [
            'permintaan' => $permintaan,
            'daftarPosko' => $daftarPosko,
            'formAction' => route('permintaan-mendesak.update', $permintaan),
            'formMethod' => 'PUT',
            'submitLabel' => 'Simpan Perubahan',
        ])
    </div>
</div>
@endsection
