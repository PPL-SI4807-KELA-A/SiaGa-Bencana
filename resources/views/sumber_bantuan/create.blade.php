@extends('layouts.app')

@section('title', 'Tambah Sumber Bantuan')
@section('judul', 'Tambah Sumber Bantuan')
@section('subjudul', 'Tambahkan pemberi bantuan ke daftar')
@section('menu_aktif', 'sumber')

@section('content')
<div class="container-fluid px-0" style="max-width: 820px;">
    <div class="sg-card p-4">
        @include('sumber_bantuan._form', [
            'sumberBantuan' => null,
            'jenisList' => $jenisList,
            'formAction' => route('sumber-bantuan.store'),
            'formMethod' => 'POST',
            'submitLabel' => 'Simpan',
        ])
    </div>
</div>
@endsection