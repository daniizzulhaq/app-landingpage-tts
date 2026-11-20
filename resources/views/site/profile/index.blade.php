@extends('layouts.public')
@section('title', 'Profil Polres')
@section('description', 'Sejarah, visi dan misi, tugas dan fungsi, serta wilayah hukum '.setting('site_tagline', 'Kepolisian Resor Timor Tengah Selatan').'.')

@section('content')
    <x-site.page-hero title="Profil Polres" :crumbs="['Profil' => null, 'Profil Polres' => null]"
                      subtitle="Mengenal lebih dekat {{ setting('site_tagline', 'Kepolisian Resor Timor Tengah Selatan') }}." />
    <section class="section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-3 d-none d-lg-block">
                    <nav class="profile-navigation sticky-side" aria-label="Daftar isi">
                        <span class="eyebrow">JELAJAHI PROFIL</span>
                        <h2 class="side-title">Kenali kami<br>lebih dekat.</h2>
                        <ul class="toc">
                            @foreach ($sections as $s)
                                <li><a href="#{{ $s->key }}" @class(['active' => $loop->first])>{{ $s->judul }}</a></li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-9">
                    <div class="d-lg-none mb-4 d-flex flex-wrap gap-2">
                        @foreach ($sections as $s)
                            <a href="#{{ $s->key }}" class="btn btn-sm btn-outline-navy">{{ $s->judul }}</a>
                        @endforeach
                    </div>
                    @foreach ($sections as $s)
                        <section class="profile-section" id="{{ $s->key }}">
                            <div class="profile-section-heading"><span class="section-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><h2 class="section-title">{{ $s->judul }}</h2></div>
                            @if ($s->gambar)
                                <a href="{{ media_url($s->gambar) }}" data-lightbox="profil-{{ $s->key }}" data-caption="{{ $s->judul }}" class="d-block mb-4 article-cover">
                                    <img src="{{ thumb_url($s->gambar) }}" alt="{{ $s->judul }}" loading="lazy">
                                </a>
                            @endif
                            @if ($s->isi)
                                <div class="prose">{!! $s->isi !!}</div>
                            @else
                                <x-site.empty message="Konten belum tersedia." />
                            @endif
                        </section>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
