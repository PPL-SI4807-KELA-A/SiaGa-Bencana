@extends('layouts.app')
@section('content')
<h4>Tambah Kategori Bencana</h4>
<form action="{{ route('kategori-bencana.store') }}" method="POST">
    @csrf
    @include('kategori_bencana._form')
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('kategori-bencana.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection