@extends('layouts.app')
@section('judul', 'Tambah Kategori Bencana')
@section('menu_aktif', 'kategori')

@section('content')
<div class="sg-card p-4">
    <form action="{{ route('kategori-bencana.store') }}" method="POST">
        @csrf
        @include('kategori_bencana._form')
        <button type="submit" class="btn btn-sg">Simpan</button>
        <a href="{{ route('kategori-bencana.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div>
@endsection