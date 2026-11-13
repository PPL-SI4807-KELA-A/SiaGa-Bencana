@extends('layouts.app')

@section('title', 'Tambah Posko')
@section('judul', 'Tambah Posko Pengungsian')
@section('subjudul', 'Catat posko dan daya tampungnya')
@section('menu_aktif', 'posko')

@push('styles')
<style>.posko-page .btn:not(.btn-sm) { font-size: .8125rem; border-radius: 8px; padding: .5rem 1rem; }</style>
@endpush

@section('content')
<div class="container-fluid px-0 posko-page" style="max-width: 820px;">
    <div class="sg-card p-4">
        @include('posko._form', [
            'posko' => null,
            'formAction' => route('posko.store'),
            'formMethod' => 'POST',
            'submitLabel' => 'Simpan',
        ])
    </div>
</div>
@endsection
