@extends('layouts.public')

@push('meta')
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'GovernmentOrganization',
        'name' => setting('site_tagline', 'Kepolisian Resor Timor Tengah Selatan'),
        'alternateName' => setting('site_name', 'Polres TTS'),
        'url' => route('home'),
        'logo' => media_url(setting('logo'), 'img/logo-polda-ntt.png'),
        'telephone' => setting('telepon'),
        'email' => setting('email'),
        'address' => setting('alamat'),
        'sameAs' => collect(config('site.social_icons'))->keys()->map(fn ($k) => setting($k))->filter()->values(),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endpush

@section('content')
    <section class="home-hero" aria-labelledby="hero-title">
        <div class="hero-grid-lines" aria-hidden="true"></div>
        <div class="container">
            <div class="home-hero-grid">
                <div class="hero-copy">
                    <div class="eyebrow hero-eyebrow"><span class="status-dot"></span>POLRES TIMOR TENGAH SELATAN</div>
                    @php $motto = setting('site_motto', 'Diberkati untuk memberkati'); @endphp
                    <h1 id="hero-title">@if (Str::contains($motto, ' ')){{ Str::beforeLast($motto, ' ') }} @endif<span>{{ Str::afterLast($motto, ' ') }}</span></h1>
                    <p>Bersama mewujudkan Timor Tengah Selatan yang aman dan nyaman. Informasi terpercaya, layanan lebih mudah, dan kepolisian yang dekat dengan Anda.</p>
                    <div class="hero-actions">
                        <a href="#layanan" class="home-button button-gold">Jelajahi layanan <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
                        <a href="{{ route('site.profile') }}" class="home-button button-glass">Kenali Polres TTS <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                    </div>
                    <div class="hero-values" aria-label="Nilai pelayanan">
                        <span><i class="bi bi-shield-check" aria-hidden="true"></i>Profesional</span>
                        <span><i class="bi bi-eye" aria-hidden="true"></i>Transparan</span>
                        <span><i class="bi bi-heart" aria-hidden="true"></i>Humanis</span>
                    </div>
                </div>
                <div class="hero-identity">
                    <div class="identity-seal">
                        <span class="seal-orbit orbit-one" aria-hidden="true"></span>
                        <span class="seal-orbit orbit-two" aria-hidden="true"></span>
                        <svg class="seal-lettering" viewBox="0 0 360 360" aria-hidden="true">
                            <defs><path id="seal-text-circle" d="M180,180 m-153,0 a153,153 0 1,1 306,0 a153,153 0 1,1 -306,0" /></defs>
                            <text><textPath href="#seal-text-circle" startOffset="0%" textLength="960">MELINDUNGI · MENGAYOMI · MELAYANI · </textPath></text>
                        </svg>
                        <img class="hero-crest" src="{{ media_url(setting('logo'), 'img/logo-polda-ntt.png') }}" width="220" height="220" alt="Lambang {{ setting('site_name', 'Polres TTS') }}" fetchpriority="high">
                        <span class="seal-star star-one" aria-hidden="true">✦</span>
                        <span class="seal-star star-two" aria-hidden="true">✦</span>
                        <span class="seal-caption">PRESISI</span>
                    </div>
                    <div class="identity-location"><span></span> TIMOR TENGAH SELATAN, NUSA TENGGARA TIMUR</div>
                </div>
            </div>
            <div class="hero-bottom-line">
                <span><i class="bi bi-geo-alt" aria-hidden="true"></i> Dekat dengan masyarakat. Hadir untuk Anda.</span>
                <a href="#layanan">Temukan kebutuhan Anda <i class="bi bi-arrow-down" aria-hidden="true"></i></a>
            </div>
        </div>
    </section>

    <section class="home-services" id="layanan" aria-labelledby="services-title">
        <div class="container">
            <div class="service-hub">
                <div class="hub-heading">
                    <div><span class="eyebrow">LAYANAN MASYARAKAT</span><h2 id="services-title">Apa yang bisa kami bantu?</h2></div>
                    <a href="{{ route('site.services.index') }}" class="home-text-link">Semua layanan <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
                </div>
                <div class="service-grid">
                    @forelse ($services as $service)
                        <a href="{{ route('site.services.show', $service->slug) }}" class="service-tile">
                            <span class="service-icon"><i class="bi {{ $service->ikon ?: 'bi-shield-check' }}" aria-hidden="true"></i></span>
                            <span class="service-tile-title">{{ Str::before($service->nama, ' (') }}</span>
                            <span class="service-tile-desc">{{ Str::limit(strip_tags($service->deskripsi), 68) ?: 'Persyaratan, prosedur, dan informasi pelayanan.' }}</span>
                            <i class="bi bi-arrow-up-right tile-arrow" aria-hidden="true"></i>
                        </a>
                    @empty
                        <a href="{{ route('site.services.index') }}" class="service-tile"><span class="service-icon"><i class="bi bi-grid" aria-hidden="true"></i></span><span class="service-tile-title">Informasi layanan</span><span class="service-tile-desc">Temukan persyaratan dan prosedur pelayanan.</span><i class="bi bi-arrow-up-right tile-arrow" aria-hidden="true"></i></a>
                    @endforelse
                    <a href="{{ route('site.complaints') }}" class="service-tile complaint-tile">
                        <span class="service-icon"><i class="bi bi-chat-square-text" aria-hidden="true"></i></span>
                        <span class="service-tile-title">Pengaduan masyarakat</span>
                        <span class="service-tile-desc">Suara Anda berarti. Sampaikan melalui kanal resmi.</span>
                        <i class="bi bi-arrow-up-right tile-arrow" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="hub-help-line"><span><i class="bi bi-info-circle" aria-hidden="true"></i> Masih punya pertanyaan seputar pelayanan?</span><a href="{{ route('site.faq') }}">Baca pertanyaan umum <i class="bi bi-arrow-right" aria-hidden="true"></i></a></div>
            </div>
        </div>
    </section>

    @if ($announcements->isNotEmpty())
        <aside class="home-announcement" aria-label="Pengumuman penting">
            <div class="container"><div class="announcement-box">
                <span class="announcement-label"><i class="bi bi-megaphone" aria-hidden="true"></i>Pengumuman</span>
                <div class="announcement-items">@foreach ($announcements as $announcement)<a href="{{ $announcement->url() }}">{{ $announcement->judul }}<i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>@endforeach</div>
                <a class="announcement-all" href="{{ route('site.posts.index', 'pengumuman') }}" aria-label="Lihat semua pengumuman"><i class="bi bi-arrow-right" aria-hidden="true"></i></a>
            </div></div>
        </aside>
    @endif

    <section class="home-section home-news" aria-labelledby="news-title">
        <div class="container">
            <div class="home-section-head"><div><span class="eyebrow">KABAR DARI POLRES</span><h2 id="news-title">Berita terbaru<span class="heading-dot">.</span></h2><p>Ikuti informasi dan kabar terkini dari wilayah Anda.</p></div><a href="{{ route('site.posts.index', 'berita') }}" class="home-button button-outline">Semua berita <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a></div>
            @if ($berita->isEmpty())
                <x-site.empty message="Belum ada berita." />
            @else
                @php $feature = $berita->first(); @endphp
                <div class="home-news-grid">
                    <a href="{{ $feature->url() }}" class="home-feature-news">
                        <div class="feature-news-image"><img src="{{ media_url($feature->thumbnail) }}" alt="" loading="lazy" decoding="async"><span class="image-badge">{{ $feature->category?->name ?? 'Berita utama' }}</span><span class="image-arrow"><i class="bi bi-arrow-up-right" aria-hidden="true"></i></span></div>
                        <div class="home-meta"><span>{{ tgl($feature->displayDate(), 'D MMMM YYYY') }}</span><span>{{ $feature->unit?->name ?? 'Humas Polres TTS' }}</span></div>
                        <h3>{{ $feature->judul }}</h3><p>{{ $feature->excerpt(160) }}</p><span class="home-text-link">Baca selengkapnya <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
                    </a>
                    <div class="home-news-list">
                        @foreach ($berita->skip(1) as $post)
                            <a href="{{ $post->url() }}" class="home-news-item"><div class="news-item-body"><span class="news-category">{{ $post->category?->name ?? 'Berita' }}</span><h3>{{ $post->judul }}</h3><span class="news-item-date">{{ tgl($post->displayDate(), 'D MMMM YYYY') }}</span></div><div class="news-item-image"><img src="{{ thumb_url($post->thumbnail) }}" alt="" loading="lazy" decoding="async"></div></a>
                        @endforeach
                        <a href="{{ route('site.posts.index', 'berita') }}" class="news-list-footer">Jelajahi kabar lainnya <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section class="home-welcome" aria-labelledby="welcome-title">
        <div class="container"><div class="welcome-layout">
            <div class="welcome-portrait">
                <div class="portrait-decoration" aria-hidden="true"></div>
                @if ($kapolres?->foto)
                    <img src="{{ media_url($kapolres->foto, 'img/avatar.svg') }}" alt="{{ $kapolres->namaLengkap() }}" loading="lazy" decoding="async">
                @else
                    <div class="portrait-emblem"><img src="{{ media_url(setting('logo'), 'img/logo-polda-ntt.png') }}" width="170" height="170" alt="Lambang {{ setting('site_name', 'Polres TTS') }}" loading="lazy"><span>MENGABDI UNTUK NEGERI</span></div>
                @endif
                <div class="portrait-name"><strong>{{ $kapolres?->namaLengkap() ?? 'Kapolres Timor Tengah Selatan' }}</strong><span>{{ $kapolres?->jabatan ?? 'Kepolisian Resor Timor Tengah Selatan' }}</span></div>
            </div>
            <div class="welcome-copy"><span class="eyebrow">SAMBUTAN KAPOLRES</span><h2 id="welcome-title">Mengabdi dengan hati.<br>Membangun kepercayaan.</h2>
                @php
                    $plain = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags(str_replace('</p>', ' </p>', (string) $sambutan?->isi)))));
                    $plain = trim(Str::after($plain, 'perlu diganti dengan data resmi.')) ?: $plain;
                @endphp
                <blockquote><span class="quote-mark" aria-hidden="true">“</span><p>{{ Str::limit($plain, 330) ?: 'Kami berkomitmen untuk terus hadir memberikan perlindungan, pengayoman, dan pelayanan terbaik bagi seluruh masyarakat Timor Tengah Selatan.' }}</p></blockquote>
                <a href="{{ route('site.leaders') }}" class="home-button button-navy">Baca sambutan lengkap <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
            </div>
        </div></div>
    </section>

    <section class="home-section" aria-labelledby="activities-title">
        <div class="container"><div class="activities-layout">
            <div class="activities-main"><div class="home-section-head"><div><span class="eyebrow">HADIR DI TENGAH MASYARAKAT</span><h2 id="activities-title">Langkah nyata kami<span class="heading-dot">.</span></h2></div><a href="{{ route('site.posts.index', 'kegiatan') }}" class="home-text-link">Semua kegiatan <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a></div>
                @if ($kegiatan->isEmpty())<x-site.empty message="Belum ada kegiatan." />@else
                    <div class="activities-grid">@foreach ($kegiatan as $post)
                        <a href="{{ $post->url() }}" class="activity-card"><div class="activity-image"><img src="{{ thumb_url($post->thumbnail) }}" alt="" loading="lazy" decoding="async"><span><i class="bi bi-arrow-up-right" aria-hidden="true"></i></span></div><div class="home-meta">{{ tgl($post->tanggal_kegiatan ?? $post->displayDate(), 'D MMMM YYYY') }}</div><h3>{{ $post->judul }}</h3>@if ($post->lokasi)<p><i class="bi bi-geo-alt" aria-hidden="true"></i>{{ Str::limit($post->lokasi, 45) }}</p>@endif</a>
                    @endforeach</div>
                @endif
            </div>
            <aside class="home-agenda" aria-labelledby="agenda-title"><div class="agenda-heading"><span class="eyebrow">CATAT TANGGALNYA</span><h2 id="agenda-title">Agenda mendatang <i class="bi bi-calendar2-week" aria-hidden="true"></i></h2><p>Kegiatan dan pelayanan untuk masyarakat.</p></div>
                @if ($agendas->isEmpty())<x-site.empty message="Belum ada agenda mendatang." icon="bi-calendar-x" />@else<div class="home-agenda-items">@foreach ($agendas as $agenda)@include('site.partials.agenda-item', ['agenda' => $agenda])@endforeach</div>@endif
                <a href="{{ route('site.agenda') }}" class="agenda-more">Lihat semua agenda <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
            </aside>
        </div></div>
    </section>

    <section class="home-education" aria-labelledby="education-title">
        <div class="container"><div class="education-layout">
            <div class="education-intro"><span class="eyebrow">WARGA PEDULI, LINGKUNGAN AMAN</span><span class="education-symbol" aria-hidden="true"><i class="bi bi-shield-check"></i></span><h2 id="education-title">Edukasi kamtibmas<span class="heading-dot">.</span></h2><p>Kenali risikonya, pahami langkahnya. Bersama menjaga diri, keluarga, dan lingkungan.</p><a href="{{ route('site.posts.index', 'edukasi') }}" class="home-text-link">Semua materi edukasi <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a></div>
            <div class="education-cards">@forelse ($edukasi as $post)
                @php $poster = $post->lampiran && ! $post->lampiranIsPdf() ? $post->lampiran : $post->thumbnail; @endphp
                <a href="{{ $post->url() }}" class="education-card"><div class="education-image"><img src="{{ thumb_url($poster) }}" alt="" loading="lazy" decoding="async"><span><i class="bi bi-arrow-up-right" aria-hidden="true"></i></span></div><span class="news-category">{{ $post->category?->name ?? 'Edukasi' }}</span><h3>{{ $post->judul }}</h3></a>
            @empty<x-site.empty message="Belum ada materi edukasi." />@endforelse</div>
        </div></div>
    </section>

    @if ($sliders->isNotEmpty())
        <section class="home-highlights" aria-label="Sorotan Polres TTS"><div class="container">
            <div id="homeHighlights" class="carousel slide" data-bs-interval="false">
                <div class="carousel-inner">@foreach ($sliders as $slide)
                    <div @class(['carousel-item', 'active' => $loop->first])><div class="highlight-content"><span class="highlight-icon"><i class="bi bi-broadcast" aria-hidden="true"></i></span><div class="highlight-copy"><span class="eyebrow">SOROTAN POLRES TTS</span><h2>{{ $slide->judul }}</h2>@if ($slide->subjudul)<p>{{ $slide->subjudul }}</p>@endif @if ($slide->link)<a href="{{ $slide->link }}" class="home-text-link">Selengkapnya <i class="bi bi-arrow-right" aria-hidden="true"></i></a>@endif</div><img class="highlight-media" src="{{ media_url($slide->gambar) }}" alt="" loading="lazy" decoding="async"></div></div>
                @endforeach</div>
                @if ($sliders->count() > 1)<div class="highlight-controls"><button type="button" data-bs-target="#homeHighlights" data-bs-slide="prev" aria-label="Sorotan sebelumnya"><i class="bi bi-arrow-left" aria-hidden="true"></i></button><button type="button" data-bs-target="#homeHighlights" data-bs-slide="next" aria-label="Sorotan berikutnya"><i class="bi bi-arrow-right" aria-hidden="true"></i></button></div>@endif
            </div>
        </div></section>
    @endif

    <section class="home-section home-gallery" aria-labelledby="gallery-title"><div class="container">
        <div class="home-section-head"><div><span class="eyebrow">CERITA DALAM LENSA</span><h2 id="gallery-title">Galeri kegiatan<span class="heading-dot">.</span></h2><p>Lebih dekat dengan keseharian dan pengabdian kami.</p></div><a href="{{ route('site.gallery.photos') }}" class="home-button button-outline">Jelajahi galeri <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a></div>
        @if ($albums->isEmpty())<x-site.empty message="Belum ada album foto." icon="bi-images" />@else
            <div class="home-gallery-grid">@foreach ($albums as $album)<a href="{{ route('site.gallery.album', $album->slug) }}" class="gallery-tile"><img src="{{ $loop->first ? media_url($album->cover?->path) : thumb_url($album->cover?->path) }}" alt="" loading="lazy" decoding="async"><span class="gallery-count"><i class="bi bi-images" aria-hidden="true"></i>{{ $album->photos_count }} foto</span><div class="gallery-caption"><h3>{{ $album->judul }}</h3><span><i class="bi bi-arrow-up-right" aria-hidden="true"></i></span></div></a>@endforeach</div>
        @endif
        @if ($videos->isNotEmpty())<div class="home-video-grid">@foreach ($videos as $video)<article class="home-video-card"><button type="button" class="video-thumb" data-embed="{{ $video->embedUrl() }}" data-title="{{ $video->judul }}" aria-label="Putar video: {{ $video->judul }}"><img src="{{ $video->thumbnailUrl() }}" alt="" loading="lazy"><span class="play"><i class="bi bi-play-fill" aria-hidden="true"></i></span></button><div><span class="eyebrow">VIDEO KEGIATAN</span><h3>{{ $video->judul }}</h3></div></article>@endforeach</div>@endif
    </div></section>

    <section class="home-emergency" aria-labelledby="emergency-title"><div class="container"><div class="emergency-layout"><div class="emergency-copy"><span class="emergency-symbol"><i class="bi bi-telephone-inbound" aria-hidden="true"></i></span><div><span class="eyebrow">KAMI SIAP MEMBANTU</span><h2 id="emergency-title">Butuh bantuan segera?</h2><p>Dalam keadaan darurat, hubungi layanan kepolisian.</p></div></div><a href="tel:110" class="emergency-call"><i class="bi bi-telephone-fill" aria-hidden="true"></i><span><strong>110</strong><small>24 jam · Bebas pulsa</small></span><i class="bi bi-arrow-up-right" aria-hidden="true"></i></a></div></div></section>

    <section class="home-section home-contact" aria-labelledby="contact-title"><div class="container">
        <div class="home-section-head"><div><span class="eyebrow">MARI TERHUBUNG</span><h2 id="contact-title">Kami dekat dengan Anda<span class="heading-dot">.</span><span class="visually-hidden"> Kontak dan lokasi</span></h2><p>Kunjungi kami atau hubungi melalui kanal yang tersedia.</p></div><a href="{{ route('site.contact') }}" class="home-text-link">Detail kontak <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a></div>
        @include('site.partials.contact-block', ['dark' => true])
    </div></section>
    <a href="#konten" class="home-back-top" aria-label="Kembali ke atas"><i class="bi bi-arrow-up" aria-hidden="true"></i></a>
@endsection
