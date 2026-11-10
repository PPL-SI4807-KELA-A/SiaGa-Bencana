@extends('layouts.app')

@section('title', 'Tambah Barang')
@section('judul', 'Tambah Barang')
@section('subjudul', 'Tambahkan barang bantuan ke daftar')

@section('content')
<div class="container-fluid px-0" style="max-width: 820px;">
    <div class="sg-card p-4">
        @include('barang._form', [
            'barang' => null,
            'kategoriList' => $kategoriList,
            'formAction' => route('barang.store'),
            'formMethod' => 'POST',
            'submitLabel' => 'Simpan',
        ])
    </div>
</div>
@endsection
