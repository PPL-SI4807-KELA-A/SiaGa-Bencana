@extends('layouts.app')

@section('title', 'Ubah Wilayah Terdampak')
@section('judul', 'Ubah Wilayah Terdampak')
@section('subjudul', 'Perbarui informasi wilayah terdampak')
@section('menu_aktif', 'wilayah')

@push('styles')
<style>
    /* Samakan ukuran tombol sekunder DAHIF-1 dengan tombol btn-sg dari layout bersama */
    .wilayah-page .btn:not(.btn-sm) { font-size: .8125rem; border-radius: 8px; padding: .5rem 1rem; }
</style>
@endpush

@section('content')
<div class="container-fluid px-0 wilayah-page" style="max-width: 820px;">
    <div class="sg-card p-4">
        @include('wilayah_terdampak._form', [
            'wilayahTerdampak' => $wilayahTerdampak,
            'formAction' => route('wilayah-terdampak.update', $wilayahTerdampak),
            'formMethod' => 'PUT',
            'submitLabel' => 'Simpan Perubahan',
        ])
    </div>
</div>
@endsection
