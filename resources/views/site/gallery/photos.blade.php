@extends('layouts.public')
@section('title', 'Galeri Foto')
@section('description', 'Album foto dokumentasi kegiatan '.setting('site_name', 'Polres TTS').'.')

@section('content')
    <x-site.page-hero title="Galeri Foto" :crumbs="['Galeri' => null, 'Foto' => null]" />
    <section class="section">
        <div class="container">
            @if ($albums->isEmpty())
                <x-site.empty message="Belum ada album foto." icon="bi-images" />
            @else
                <div class="row g-4">
                    @foreach ($albums as $al)
                        <div class="col-sm-6 col-lg-4">
                            <a href="{{ route('site.gallery.album', $al->slug) }}" class="card-clean">
                                <div class="card-media" style="aspect-ratio:4/3">
                                    <img src="{{ thumb_url($al->cover?->path) }}" alt="" loading="lazy">
                                    <span class="album-count"><i class="bi bi-images me-1"></i>{{ $al->photos_count }} foto</span>
                                </div>
                                <div class="card-body-clean">
                                    <h2 class="card-title-clean h6">{{ $al->judul }}</h2>
                                    <div class="card-meta mb-0"><span><i class="bi bi-calendar3"></i>{{ tgl($al->tanggal ?? $al->published_at) }}</span></div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="mt-5 d-flex justify-content-center">{{ $albums->links() }}</div>
            @endif
        </div>
    </section>
@endsection
