@extends('layouts.app')
@section('title', 'Ubah Fasilitas Posko')
@section('judul', 'Ubah Fasilitas Posko')
@section('subjudul', 'Perbarui catatan fasilitas')
@section('menu_aktif', 'fasilitas')
@section('content')
<div class="sg-card p-4">
    @if ($errors->any())<div class="alert alert-danger"><strong>Periksa kembali data yang dimasukkan.</strong><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form method="POST" action="{{ route('fasilitas-posko.update', $fasilitasPosko) }}">@method('PUT')@include('fasilitas_posko._form', ['fasilitasPosko' => $fasilitasPosko])<div class="d-flex gap-2"><button type="submit" class="btn btn-sg">Simpan Perubahan</button><a href="{{ route('fasilitas-posko.index') }}" class="btn btn-outline-secondary">Batal</a></div></form>
</div>
@endsection
