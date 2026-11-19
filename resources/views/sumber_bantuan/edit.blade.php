@extends('layouts.app')

@section('title', 'Ubah Sumber Bantuan')
@section('judul', 'Ubah Sumber Bantuan')
@section('subjudul', 'Perbarui informasi dan status sumber bantuan')
@section('menu_aktif', 'sumber')

@section('content')
<div class="container-fluid px-0" style="max-width: 820px;">
    <div class="sg-card p-4">
        @include('sumber_bantuan._form', [
            'sumberBantuan' => $sumberBantuan,
            'jenisList' => $jenisList,
            'formAction' => route('sumber-bantuan.update', $sumberBantuan),
            'formMethod' => 'PUT',
            'submitLabel' => 'Simpan Perubahan',
        ])
    </div>
</div>
@endsection