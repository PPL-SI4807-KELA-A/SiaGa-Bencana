@extends('layouts.app')
@section('content')
<h4>Ubah Kategori Bencana</h4>
<form action="{{ route('kategori-bencana.update', $kategori) }}" method="POST">
    @csrf @method('PUT')
    @include('kategori_bencana._form')
    <button class="btn btn-primary">Perbarui</button>
    <a href="{{ route('kategori-bencana.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection