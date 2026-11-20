@extends('layouts.admin')
@section('title', $video->exists ? 'Ubah Video' : 'Tambah Video')

@section('content')
    <x-admin.page-header :title="$video->exists ? 'Ubah Video' : 'Tambah Video'" :back="route('admin.videos.index')" />
    <div class="card" style="max-width:760px"><div class="card-body">
        <form method="POST" action="{{ $video->exists ? route('admin.videos.update', $video) : route('admin.videos.store') }}">
            @csrf
            @if ($video->exists) @method('PUT') @endif
            <x-admin.input name="judul" label="Judul" :value="$video->judul" required />
            <x-admin.input type="url" name="youtube_url" label="Tautan YouTube" :value="$video->youtube_url" required help="Contoh: https://www.youtube.com/watch?v=XXXXXXXXXXX atau https://youtu.be/XXXXXXXXXXX" />
            <x-admin.textarea name="deskripsi" label="Deskripsi" :value="$video->deskripsi" rows="3" />
            <x-admin.input type="date" name="tanggal" label="Tanggal" :value="optional($video->tanggal)->format('Y-m-d')" style="max-width:220px" />
            @if ($video->exists && $video->embedUrl())
                <div class="ratio ratio-16x9 mb-3 rounded overflow-hidden"><iframe src="{{ $video->embedUrl() }}" title="{{ $video->judul }}" loading="lazy" allowfullscreen></iframe></div>
            @endif
            <x-admin.form-actions :cancel="route('admin.videos.index')" />
        </form>
    </div></div>
@endsection
