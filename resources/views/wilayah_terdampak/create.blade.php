@extends('layouts.app')

@section('title', 'Tambah Wilayah Terdampak')
@section('judul', 'Tambah Wilayah Terdampak')
@section('subjudul', 'Catat dampak bencana pada wilayah')
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
            'wilayahTerdampak' => null,
            'formAction' => route('wilayah-terdampak.store'),
            'formMethod' => 'POST',
            'submitLabel' => 'Simpan',
        ])
    </div>
</div>
@endsection
