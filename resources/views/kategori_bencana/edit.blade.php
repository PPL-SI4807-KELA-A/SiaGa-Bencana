@extends('layouts.app')
@section('judul', 'Ubah Kategori Bencana')
@section('menu_aktif', 'kategori')

@section('content')
<div class="sg-card p-4">
    <form action="{{ route('kategori-bencana.update', $kategori) }}" method="POST">
        @csrf
        @method('PUT')
        @include('kategori_bencana._form')
        <button type="submit" class="btn btn-sg">Perbarui</button>
        <a href="{{ route('kategori-bencana.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div>
@endsection