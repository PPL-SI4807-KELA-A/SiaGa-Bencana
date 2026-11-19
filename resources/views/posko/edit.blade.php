@extends('layouts.app')

@section('title', 'Ubah Posko')
@section('judul', 'Ubah Posko Pengungsian')
@section('subjudul', 'Perbarui informasi dan daya tampung posko')
@section('menu_aktif', 'posko')

@push('styles')
<style>.posko-page .btn:not(.btn-sm) { font-size: .8125rem; border-radius: 8px; padding: .5rem 1rem; }</style>
@endpush

@section('content')
<div class="container-fluid px-0 posko-page" style="max-width: 820px;">
    <div class="sg-card p-4">
        @include('posko._form', [
            'posko' => $posko,
            'formAction' => route('posko.update', $posko),
            'formMethod' => 'PUT',
            'submitLabel' => 'Simpan Perubahan',
        ])
    </div>
</div>
@endsection
