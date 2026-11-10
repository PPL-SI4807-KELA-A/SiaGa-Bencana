@extends('layouts.app')

@section('title', 'Ubah Barang')
@section('judul', 'Ubah Barang')
@section('subjudul', 'Perbarui informasi dan status barang')

@section('content')
<div class="container-fluid px-0" style="max-width: 820px;">
    <div class="sg-card p-4">
        @include('barang._form', [
            'barang' => $barang,
            'kategoriList' => $kategoriList,
            'formAction' => route('barang.update', $barang),
            'formMethod' => 'PUT',
            'submitLabel' => 'Simpan Perubahan',
        ])
    </div>
</div>
@endsection
