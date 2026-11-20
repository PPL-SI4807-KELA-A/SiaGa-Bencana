@extends('layouts.guest')

@section('title', 'Ajukan Permintaan Mendesak')

@section('content')
<div class="container-fluid px-0" style="max-width: 820px;">
    <div class="sg-card p-4">
        <h1 class="h4 mb-2">Ajukan Permintaan Kebutuhan Mendesak</h1>
        <p class="text-body-secondary mb-4">Isi kebutuhan dan lokasi. Simpan kode pelacakan yang ditampilkan setelah pengajuan.</p>

        @include('permintaan_mendesak._form', [
            'permintaan' => null,
            'daftarPosko' => $daftarPosko,
            'formAction' => route('permintaan-mendesak.store'),
            'formMethod' => 'POST',
            'submitLabel' => 'Ajukan Permintaan',
        ])
    </div>
</div>
@endsection
