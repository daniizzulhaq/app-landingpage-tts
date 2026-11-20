@extends('layouts.public')
@php
    $listTitle = App\Models\Post::typeLabel($type, true);
    $image = $post->thumbnail ? media_url($post->thumbnail) : ($post->images->first() ? media_url($post->images->first()->path) : '');
    $isImageAttachment = $post->lampiran && ! $post->lampiranIsPdf();
@endphp
@section('title', $post->judul)
@section('description', $post->excerpt(160))
@section('og_type', 'article')
@section('og_image', $image)

@push('meta')
    <meta property="article:published_time" content="{{ optional($post->published_at)->toIso8601String() }}">
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => $type === 'berita' ? 'NewsArticle' : 'Article',
        'headline' => Str::limit($post->judul, 110),
        'description' => $post->excerpt(160),
        'image' => $image ? [$image] : [],
        'datePublished' => optional($post->published_at)->toIso8601String(),
        'dateModified' => $post->updated_at->toIso8601String(),
        'author' => ['@type' => 'Organization', 'name' => $post->unit?->name ?? setting('site_name', 'Polres TTS')],
        'publisher' => ['@type' => 'Organization', 'name' => setting('site_name', 'Polres TTS'),
            'logo' => ['@type' => 'ImageObject', 'url' => media_url(setting('logo'), 'img/logo-placeholder.svg')]],
        'mainEntityOfPage' => url()->current(),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endpush

@section('content')
    <x-site.page-hero :title="$listTitle" heading-tag="p" :crumbs="['Publikasi' => null, $listTitle => route('site.posts.index', $type), $post->judul => null]" />

    <article class="section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8"><div class="article-panel">
                    @if ($post->category)<div class="card-tag">{{ $post->category->name }}</div>@endif
                    <h1 class="article-title mb-3">{{ $post->judul }}</h1>
                    <div class="article-meta mb-4">
                        <span><i class="bi bi-calendar3"></i><time datetime="{{ optional($post->published_at)->toDateString() }}">{{ tgl($post->displayDate(), 'dddd, D MMMM YYYY') }}</time></span>
                        <span><i class="bi bi-diagram-3"></i>{{ $post->unit?->name ?? setting('site_name', 'Polres TTS') }}</span>
                        @if ($post->author)<span><i class="bi bi-person"></i>{{ $post->author->name }}</span>@endif
                        <span><i class="bi bi-eye"></i>{{ number_format($post->views) }} kali dilihat</span>
                    </div>

                    @if ($type === 'kegiatan' && ($post->tanggal_kegiatan || $post->lokasi))
                        <div class="info-box mb-4">
                            <div class="row g-3">
                                @if ($post->tanggal_kegiatan)<div class="col-sm-6"><dl class="mb-0"><dt>Tanggal Kegiatan</dt><dd>{{ tgl($post->tanggal_kegiatan, 'dddd, D MMMM YYYY') }}</dd></dl></div>@endif
                                @if ($post->lokasi)<div class="col-sm-6"><dl class="mb-0"><dt>Lokasi</dt><dd>{{ $post->lokasi }}</dd></dl></div>@endif
                            </div>
                        </div>
                    @endif

                    @if ($post->thumbnail)
                        <figure class="article-cover mb-4">
                            <a href="{{ media_url($post->thumbnail) }}" data-lightbox="artikel" data-caption="{{ $post->judul }}">
                                <img src="{{ media_url($post->thumbnail) }}" alt="{{ $post->judul }}" fetchpriority="high">
                            </a>
                        </figure>
                    @endif

                    @if ($post->ringkasan)<p class="lead-text mb-4">{{ $post->ringkasan }}</p>@endif

                    {{-- Edukasi: poster/infografis yang bisa diperbesar dan diunduh --}}
                    @if ($type === 'edukasi' && $isImageAttachment)
                        <figure class="mb-4 text-center">
                            <a href="{{ media_url($post->lampiran) }}" data-lightbox="artikel" data-caption="Infografis: {{ $post->judul }}" class="d-inline-block article-cover border">
                                <img src="{{ media_url($post->lampiran) }}" alt="Infografis {{ $post->judul }}" loading="lazy" style="max-height:none;object-fit:contain">
                            </a>
                            <figcaption class="small text-muted mt-2">Klik gambar untuk memperbesar.</figcaption>
                        </figure>
                    @endif

                    <div class="prose">{!! $post->isi !!}</div>

                    @if ($post->images->isNotEmpty())
                        <h2 class="h5 mt-5 mb-3">Galeri Foto</h2>
                        <div class="gallery-grid">
                            @foreach ($post->images as $img)
                                <a href="{{ media_url($img->path) }}" data-lightbox="artikel" data-caption="{{ $img->caption }}">
                                    <img src="{{ thumb_url($img->path) }}" alt="{{ $img->caption ?: 'Foto '.$loop->iteration }}" loading="lazy">
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if ($post->lampiran)
                        <a href="{{ media_url($post->lampiran) }}" class="file-link mt-4" download target="_blank" rel="noopener">
                            <i class="bi {{ $post->lampiranIsPdf() ? 'bi-file-earmark-pdf' : 'bi-file-earmark-image' }}"></i>
                            <span class="fw-semibold">Unduh {{ $type === 'edukasi' ? 'poster / infografis' : 'lampiran' }}</span>
                            <span class="ext">{{ strtoupper(pathinfo($post->lampiran, PATHINFO_EXTENSION)) }}</span>
                        </a>
                    @endif

                    <hr class="my-4">
                    <x-site.share :url="url()->current()" :title="$post->judul" />
                    </div>
                </div>

                <aside class="col-lg-4">
                    <div class="sidebar-card sticky-side">
                        <h2>{{ App\Models\Post::typeLabel($type) }} Terkait</h2>
                        @forelse ($related as $r)
                            <a href="{{ $r->url() }}" class="list-item">
                                <img src="{{ thumb_url($r->thumbnail) }}" alt="" loading="lazy" style="width:88px;height:64px">
                                <div class="min-w-0">
                                    <div class="title" style="font-size:.9rem">{{ $r->judul }}</div>
                                    <div class="small text-muted mt-1">{{ tgl($r->displayDate(), 'D MMM YYYY') }}</div>
                                </div>
                            </a>
                        @empty
                            <p class="text-muted small mb-0">Belum ada konten terkait.</p>
                        @endforelse
                        <a href="{{ route('site.posts.index', $type) }}" class="btn btn-outline-navy w-100 mt-3">Lihat semua {{ strtolower(App\Models\Post::typeLabel($type)) }}</a>
                    </div>
                </aside>
            </div>
        </div>
    </article>
@endsection
