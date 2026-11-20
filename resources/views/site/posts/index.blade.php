@extends('layouts.public')
@php
    $title = App\Models\Post::typeLabel($type, true);
    $descs = [
        'berita' => 'Berita dan siaran pers terbaru',
        'kegiatan' => 'Dokumentasi kegiatan',
        'pengumuman' => 'Pengumuman resmi',
        'edukasi' => 'Materi edukasi keamanan dan ketertiban masyarakat',
    ];
@endphp
@section('title', $title.($posts->currentPage() > 1 ? ' — Halaman '.$posts->currentPage() : ''))
@section('description', $descs[$type].' '.setting('site_tagline', 'Polres TTS').'.')

@section('content')
    <x-site.page-hero :title="$title" :crumbs="['Publikasi' => null, $title => null]" :subtitle="$descs[$type].'.'" />
    <section class="section">
        <div class="container">
            <form method="GET" class="filter-bar" role="search" aria-label="Saring {{ strtolower($title) }}">
                <div class="row g-2">
                    <div class="col-12 col-lg">
                        <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari judul..." aria-label="Cari judul">
                    </div>
                    @if ($categories->isNotEmpty())
                        <div class="col-6 col-lg-3">
                            <select name="kategori" class="form-select" aria-label="Kategori">
                                <option value="">Semua kategori</option>
                                @foreach ($categories as $c)<option value="{{ $c->slug }}" @selected(request('kategori') === $c->slug)>{{ $c->name }}</option>@endforeach
                            </select>
                        </div>
                    @endif
                    @if ($units->isNotEmpty())
                        <div class="col-6 col-lg-3">
                            <select name="unit" class="form-select" aria-label="Unit">
                                <option value="">Semua unit</option>
                                @foreach ($units as $u)<option value="{{ $u->slug }}" @selected(request('unit') === $u->slug)>{{ $u->name }}</option>@endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-12 col-lg-auto d-flex gap-2">
                        <button class="btn btn-navy flex-grow-1"><i class="bi bi-funnel me-1"></i>Terapkan</button>
                        @if (request()->hasAny(['q', 'kategori', 'unit']))
                            <a href="{{ route('site.posts.index', $type) }}" class="btn btn-light">Reset</a>
                        @endif
                    </div>
                </div>
            </form>

            @if ($posts->isEmpty())
                <x-site.empty message="Belum ada data." />
            @else
                <div class="row g-4">
                    @foreach ($posts as $p)
                        <div class="col-sm-6 col-lg-4"><x-site.post-card :post="$p" /></div>
                    @endforeach
                </div>
                <div class="mt-5 d-flex justify-content-center">{{ $posts->onEachSide(1)->links() }}</div>
            @endif
        </div>
    </section>
@endsection
