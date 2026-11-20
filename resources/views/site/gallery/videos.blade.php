@extends('layouts.public')
@section('title', 'Galeri Video')
@section('description', 'Video kegiatan dan informasi dari '.setting('site_name', 'Polres TTS').'.')

@section('content')
    <x-site.page-hero title="Galeri Video" :crumbs="['Galeri' => null, 'Video' => null]" />
    <section class="section">
        <div class="container">
            @if ($videos->isEmpty())
                <x-site.empty message="Belum ada video." icon="bi-camera-video" />
            @else
                <div class="row g-4">
                    @foreach ($videos as $v)
                        <div class="col-md-6 col-lg-4">
                            <div class="card-clean">
                                <button type="button" class="video-thumb" data-embed="{{ $v->embedUrl() }}" data-title="{{ $v->judul }}" aria-label="Putar video: {{ $v->judul }}">
                                    <img src="{{ $v->thumbnailUrl() }}" alt="" loading="lazy"><span class="play"><i class="bi bi-play-fill"></i></span>
                                </button>
                                <div class="card-body-clean">
                                    <h2 class="card-title-clean h6">{{ $v->judul }}</h2>
                                    @if ($v->deskripsi)<p class="card-text-clean">{{ $v->deskripsi }}</p>@endif
                                    @if ($v->tanggal)<div class="card-meta mt-2 mb-0"><span><i class="bi bi-calendar3"></i>{{ tgl($v->tanggal) }}</span></div>@endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-5 d-flex justify-content-center">{{ $videos->links() }}</div>
            @endif
        </div>
    </section>
@endsection
