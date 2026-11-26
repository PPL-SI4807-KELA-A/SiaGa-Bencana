@extends('layouts.app')
@section('title', 'Tambah Fasilitas Posko')
@section('judul', 'Tambah Fasilitas Posko')
@section('subjudul', 'Catat fasilitas pada posko Anda')
@section('menu_aktif', 'fasilitas')
@section('content')
<div class="sg-card p-4">
    @if ($errors->any())<div class="alert alert-danger"><strong>Periksa kembali data yang dimasukkan.</strong><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form method="POST" action="{{ route('fasilitas-posko.store') }}">@include('fasilitas_posko._form', ['fasilitasPosko' => null])<div class="d-flex gap-2"><button type="submit" class="btn btn-sg">Simpan</button><a href="{{ route('fasilitas-posko.index') }}" class="btn btn-outline-secondary">Batal</a></div></form>
</div>
@endsection
