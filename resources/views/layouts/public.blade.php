@php
    $siteName = setting('site_name', 'Polres TTS');
    $tagline = setting('site_tagline', 'Kepolisian Resor Timor Tengah Selatan');
    $pageTitle = trim($__env->yieldContent('title'));
    $fullTitle = $pageTitle ? $pageTitle.' | '.$siteName : $siteName.' — '.$tagline;
    $metaDesc = Str::limit(trim($__env->yieldContent('description')) ?: setting('meta_description', $tagline), 160);
    $ogImage = trim($__env->yieldContent('og_image')) ?: (setting('og_image') ? media_url(setting('og_image')) : site_logo_url());
    $logo = site_logo_url();
    $nav = [
        ['Beranda', route('home'), 'home', []],
        ['Profil', null, 'site.profile*|site.leaders|site.structure|site.units.*|site.polsek', [
            ['Profil Polres', route('site.profile')],
            ['Profil Pimpinan', route('site.leaders')],
            ['Struktur Organisasi', route('site.structure')],
            ['Satuan Kerja', route('site.units.index')],
            ['Direktori Polsek', route('site.polsek')],
        ]],
        ['Layanan', null, 'site.services.*|site.complaints|site.faq', [
            ['Informasi Layanan', route('site.services.index')],
            ['Pengaduan dan Bantuan', route('site.complaints')],
            ['FAQ', route('site.faq')],
        ]],
        ['Publikasi', null, 'site.posts.*|site.agenda', [
            ['Berita dan Siaran Pers', route('site.posts.index', 'berita')],
            ['Kegiatan', route('site.posts.index', 'kegiatan')],
            ['Agenda', route('site.agenda')],
            ['Pengumuman', route('site.posts.index', 'pengumuman')],
            ['Edukasi Kamtibmas', route('site.posts.index', 'edukasi')],
        ]],
        ['Galeri', null, 'site.gallery.*', [
            ['Foto', route('site.gallery.photos')],
            ['Video', route('site.gallery.videos')],
        ]],
        ['Informasi Publik', null, 'site.ppid*|site.contact', [
            ['PPID', route('site.ppid')],
            ['Kontak dan Lokasi', route('site.contact')],
        ]],
    ];
    $isActive = fn ($patterns) => collect(explode('|', $patterns))->contains(fn ($p) => request()->routeIs($p));
    $socials = collect(config('site.social_icons'))->filter(fn ($icon, $key) => setting($key));
    $isHome = request()->routeIs('home');
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ $metaDesc }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="theme-color" content="#0b1f3a">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="{{ $pageTitle ?: $siteName }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle ?: $siteName }}">
    <meta name="twitter:description" content="{{ $metaDesc }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    @stack('meta')
    <link rel="icon" href="{{ $logo }}">
    <link rel="preload" href="{{ asset('fonts/plus-jakarta-sans/latin-variable.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/site.css') }}?v=5" rel="stylesheet">
    <link href="{{ asset('css/theme.css') }}?v=4" rel="stylesheet">
    @unless ($isHome)
        <link href="{{ asset('css/pages.css') }}?v=3" rel="stylesheet">
    @endunless
    @stack('styles')
</head>
<body @class(['public-site', 'home-page' => $isHome, 'public-inner' => ! $isHome])>
<a href="#konten" class="visually-hidden-focusable skip-link">Langsung ke konten</a>

<div class="topbar d-none d-lg-block">
    <div class="container d-flex align-items-center gap-4">
        <span class="portal-label"><i class="bi bi-shield-check me-2" aria-hidden="true"></i>Portal informasi &amp; pelayanan masyarakat</span>
        @if (setting('telepon'))
            <a href="{{ tel_link(setting('telepon')) }}"><i class="bi bi-telephone me-1"></i>{{ setting('telepon') }}</a>
        @endif
        @if (setting('email'))
            <a href="mailto:{{ setting('email') }}"><i class="bi bi-envelope me-1"></i>{{ setting('email') }}</a>
        @endif
        <div class="ms-auto d-flex gap-3">
            @foreach ($socials as $key => $icon)
                <a href="{{ setting($key) }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($key) }}"><i class="bi {{ $icon }}"></i></a>
            @endforeach
        </div>
    </div>
</div>

<header class="site-header sticky-top">
    <nav class="navbar navbar-expand-xl navbar-light" aria-label="Navigasi utama">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <img src="{{ $logo }}" alt="Logo {{ $siteName }}" width="44" height="44">
                <span class="brand-text">
                    <span class="brand-name">{{ $siteName }}</span>
                    <span class="brand-tagline d-none d-sm-block">{{ $tagline }}</span>
                </span>
            </a>
            <div class="d-flex align-items-center gap-2 order-xl-last ms-auto ms-xl-3">
                <button class="btn btn-icon" type="button" data-bs-toggle="modal" data-bs-target="#searchModal" aria-label="Cari">
                    <i class="bi bi-search"></i>
                </button>
                <a href="tel:110" class="btn btn-emergency btn-110" aria-label="Telepon darurat 110">
                    <i class="bi bi-telephone-fill"></i><span>110</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Buka menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto">
                    @foreach ($nav as [$label, $url, $pattern, $children])
                        @if (empty($children))
                            <li class="nav-item"><a class="nav-link {{ $isActive($pattern) ? 'active' : '' }}" href="{{ $url }}" @if ($isActive($pattern)) aria-current="page" @endif>{{ $label }}</a></li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ $isActive($pattern) ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{ $label }}<i class="bi bi-chevron-down nav-caret" aria-hidden="true"></i></a>
                                <ul class="dropdown-menu">
                                    @foreach ($children as [$cLabel, $cUrl])
                                        <li><a class="dropdown-item {{ url()->current() === $cUrl ? 'active' : '' }}" href="{{ $cUrl }}">{{ $cLabel }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </nav>
</header>

<main id="konten">
    @yield('content')
</main>

<footer class="site-footer">
    <div class="tenun" aria-hidden="true"></div>
    <div class="container footer-main">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <img src="{{ $logo }}" alt="" width="64" height="64" loading="lazy" style="object-fit:contain">
                    <div>
                        <div class="footer-brand">{{ $siteName }}</div>
                        <div class="small" style="color:var(--brass-light)">{{ $tagline }}</div>
                    </div>
                </div>
                <p class="small mb-3">{{ setting('site_motto', 'Diberkati untuk memberkati') }}</p>
                @if (setting('alamat'))<p class="small mb-2"><i class="bi bi-geo-alt me-2"></i>{{ setting('alamat') }}</p>@endif
                @if (setting('jam_pelayanan'))<p class="small mb-0" style="white-space:pre-line"><i class="bi bi-clock me-2"></i>{{ setting('jam_pelayanan') }}</p>@endif
            </div>
            <div class="col-6 col-lg-2">
                <h2 class="footer-title">Profil</h2>
                <ul class="footer-links">
                    <li><a href="{{ route('site.profile') }}">Profil Polres</a></li>
                    <li><a href="{{ route('site.leaders') }}">Profil Pimpinan</a></li>
                    <li><a href="{{ route('site.structure') }}">Struktur Organisasi</a></li>
                    <li><a href="{{ route('site.units.index') }}">Satuan Kerja</a></li>
                    <li><a href="{{ route('site.polsek') }}">Direktori Polsek</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h2 class="footer-title">Layanan</h2>
                <ul class="footer-links">
                    <li><a href="{{ route('site.services.index') }}">Informasi Layanan</a></li>
                    <li><a href="{{ route('site.complaints') }}">Pengaduan dan Bantuan</a></li>
                    <li><a href="{{ route('site.faq') }}">FAQ</a></li>
                    <li><a href="{{ route('site.ppid') }}">PPID</a></li>
                    <li><a href="{{ route('site.contact') }}">Kontak dan Lokasi</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h2 class="footer-title">Keadaan Darurat</h2>
                <a href="tel:110" class="footer-110">
                    <i class="bi bi-telephone-fill"></i>
                    <span><strong>110</strong><small>Layanan darurat 24 jam, bebas pulsa</small></span>
                </a>
                <ul class="footer-links mt-3">
                    @if (setting('telepon'))<li><a href="{{ tel_link(setting('telepon')) }}"><i class="bi bi-telephone me-2"></i>{{ setting('telepon') }}</a></li>@endif
                    @if (setting('email'))<li><a href="mailto:{{ setting('email') }}"><i class="bi bi-envelope me-2"></i>{{ setting('email') }}</a></li>@endif
                </ul>
                @if ($socials->isNotEmpty())
                    <div class="footer-social mt-3">
                        @foreach ($socials as $key => $icon)
                            <a href="{{ setting($key) }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($key) }}"><i class="bi {{ $icon }}"></i></a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; {{ now()->year }} {{ $siteName }}. Hak cipta dilindungi.</span>
            <a href="{{ route('admin.login') }}" rel="nofollow">Admin</a>
        </div>
    </div>
</footer>

{{-- Modal pencarian --}}
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body p-3 p-md-4">
                <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                    <h2 class="h6 mb-0" id="searchModalLabel">Cari berita, layanan, atau dokumen</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup pencarian"></button>
                </div>
                <form action="{{ route('site.search') }}" method="GET" role="search">
                    <div class="input-group input-group-lg">
                        <input type="search" name="q" class="form-control" placeholder="Contoh: SKCK, SIM, lalu lintas" aria-label="Kata kunci" minlength="2" required>
                        <button class="btn btn-navy" type="submit"><i class="bi bi-search"></i><span class="visually-hidden">Cari</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Lightbox gambar --}}
<div class="modal fade lightbox" id="lightbox" tabindex="-1" aria-label="Pratinjau gambar" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0">
            <div class="lightbox-toolbar">
                <span class="lightbox-counter small"></span>
                <a href="#" class="btn btn-sm btn-light lightbox-download" download><i class="bi bi-download"></i> Unduh</a>
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="lightbox-stage">
                <button type="button" class="lightbox-nav prev" aria-label="Sebelumnya"><i class="bi bi-chevron-left"></i></button>
                <img src="" alt="" class="lightbox-img">
                <button type="button" class="lightbox-nav next" aria-label="Berikutnya"><i class="bi bi-chevron-right"></i></button>
            </div>
            <p class="lightbox-caption"></p>
        </div>
    </div>
</div>

<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/site.js') }}?v=2"></script>
@stack('scripts')
</body>
</html>
