@extends('layouts.public')
@section('title', $album->judul)
@section('description', $album->deskripsi ?: 'Album foto '.$album->judul.' — '.setting('site_name', 'Polres TTS').'.')
@section('og_image', $album->photos->first() ? media_url($album->photos->first()->path) : '')

@section('content')
    <x-site.page-hero :title="$album->judul" :subtitle="$album->deskripsi" :crumbs="['Galeri' => null, 'Foto' => route('site.gallery.photos'), $album->judul => null]">
        <div class="small text-white-50 mt-2">
            <i class="bi bi-calendar3 me-1"></i>{{ tgl($album->tanggal ?? $album->published_at) }}
            · <i class="bi bi-images ms-1 me-1"></i>{{ $album->photos->count() }} foto
            @if ($album->unit) · <i class="bi bi-diagram-3 ms-1 me-1"></i>{{ $album->unit->name }}@endif
        </div>
    </x-site.page-hero>
    <section class="section">
        <div class="container">
            @if ($album->photos->isEmpty())
                <x-site.empty message="Belum ada foto di album ini." icon="bi-images" />
            @else
                <div class="gallery-grid cols-4">
                    @foreach ($album->photos as $photo)
                        <a href="{{ media_url($photo->path) }}" data-lightbox="album" data-caption="{{ $photo->caption }}">
                            <img src="{{ thumb_url($photo->path) }}" alt="{{ $photo->caption ?: $album->judul.' — foto '.$loop->iteration }}" loading="lazy">
                        </a>
                    @endforeach
                </div>
            @endif
            <div class="mt-5"><x-site.share :url="url()->current()" :title="$album->judul" /></div>
        </div>
    </section>
@endsection
