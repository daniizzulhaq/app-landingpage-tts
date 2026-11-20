@props(['title', 'subtitle' => null, 'crumbs' => [], 'headingTag' => 'h1'])
{{-- $crumbs: ['Label' => url|null, ...] — item terakhir otomatis aktif --}}
@php
    $trail = ['Beranda' => route('home')] + $crumbs;
    $ld = [];
    $pos = 1;
    foreach ($trail as $label => $url) {
        $ld[] = ['@type' => 'ListItem', 'position' => $pos++, 'name' => $label, 'item' => $url ?: url()->current()];
    }
    $group = match (true) {
        request()->routeIs('site.profile', 'site.leaders', 'site.structure', 'site.units.*', 'site.polsek') => 'profil',
        request()->routeIs('site.services.*', 'site.complaints', 'site.faq') => 'layanan',
        request()->routeIs('site.posts.*', 'site.agenda') => 'publikasi',
        request()->routeIs('site.gallery.*') => 'galeri',
        request()->routeIs('site.ppid*', 'site.contact') => 'informasi',
        default => 'pencarian',
    };
    $identity = [
        'profil' => ['TENTANG POLRES TTS', 'bi-shield-check', 'Mengenal lebih dekat pengabdian, organisasi, dan pelayanan kami untuk masyarakat.'],
        'layanan' => ['PELAYANAN MASYARAKAT', 'bi-person-vcard', 'Informasi yang jelas untuk mengurus kebutuhan layanan kepolisian Anda.'],
        'publikasi' => ['INFORMASI & PUBLIKASI', 'bi-newspaper', 'Kabar, kegiatan, dan informasi terkini dari Polres Timor Tengah Selatan.'],
        'galeri' => ['CERITA DALAM LENSA', 'bi-camera', 'Dokumentasi kebersamaan, pelayanan, dan pengabdian di tengah masyarakat.'],
        'informasi' => ['TERBUKA & TERHUBUNG', 'bi-building-check', 'Akses informasi publik dan terhubung dengan layanan Polres TTS.'],
        'pencarian' => ['PUSAT INFORMASI', 'bi-search', 'Temukan berita, layanan, dan dokumen yang Anda butuhkan dalam satu tempat.'],
    ][$group];
    $isPolsek = request()->routeIs('site.polsek') || array_key_exists('Direktori Polsek', $crumbs);
    $sectionLinks = [
        'profil' => [
            ['Profil Polres', route('site.profile'), request()->routeIs('site.profile')],
            ['Pimpinan', route('site.leaders'), request()->routeIs('site.leaders')],
            ['Struktur Organisasi', route('site.structure'), request()->routeIs('site.structure')],
            ['Satuan Kerja', route('site.units.index'), request()->routeIs('site.units.*') && ! $isPolsek],
            ['Direktori Polsek', route('site.polsek'), $isPolsek],
        ],
        'layanan' => [
            ['Informasi Layanan', route('site.services.index'), request()->routeIs('site.services.*')],
            ['Pengaduan & Bantuan', route('site.complaints'), request()->routeIs('site.complaints')],
            ['Pertanyaan Umum', route('site.faq'), request()->routeIs('site.faq')],
        ],
        'publikasi' => [
            ['Berita', route('site.posts.index', 'berita'), request()->route('type') === 'berita'],
            ['Kegiatan', route('site.posts.index', 'kegiatan'), request()->route('type') === 'kegiatan'],
            ['Agenda', route('site.agenda'), request()->routeIs('site.agenda')],
            ['Pengumuman', route('site.posts.index', 'pengumuman'), request()->route('type') === 'pengumuman'],
            ['Edukasi Kamtibmas', route('site.posts.index', 'edukasi'), request()->route('type') === 'edukasi'],
        ],
        'galeri' => [
            ['Galeri Foto', route('site.gallery.photos'), request()->routeIs('site.gallery.photos', 'site.gallery.album')],
            ['Galeri Video', route('site.gallery.videos'), request()->routeIs('site.gallery.videos')],
        ],
        'informasi' => [
            ['PPID & Informasi Publik', route('site.ppid'), request()->routeIs('site.ppid*')],
            ['Kontak & Lokasi', route('site.contact'), request()->routeIs('site.contact')],
        ],
        'pencarian' => [],
    ][$group];
@endphp
<section class="page-hero">
    <div class="page-hero-pattern" aria-hidden="true"></div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                @foreach ($crumbs as $label => $url)
                    @if ($loop->last)
                        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($label, 60) }}</li>
                    @elseif (! $url)
                        <li class="breadcrumb-item text-white-50">{{ $label }}</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
                    @endif
                @endforeach
            </ol>
        </nav>
        <div class="page-hero-layout">
            <div class="page-hero-copy">
                <span class="eyebrow"><span></span>{{ $identity[0] }}</span>
                <{{ $headingTag }} class="page-hero-title">{{ $title }}<span class="heading-dot">.</span></{{ $headingTag }}>
                <p class="page-hero-description">{{ $subtitle ?: $identity[2] }}</p>
                {{ $slot }}
            </div>
            <div class="page-hero-mark" aria-hidden="true">
                <span class="page-mark-orbit"></span>
                <i class="bi {{ $identity[1] }}"></i>
                <span class="page-mark-caption">POLRES TTS</span>
            </div>
        </div>
    </div>
</section>
@if ($sectionLinks)
    <nav class="section-navigation" aria-label="Navigasi {{ $group }}">
        <div class="container"><div class="section-navigation-links">
            @foreach ($sectionLinks as [$label, $url, $active])
                <a href="{{ $url }}" @class(['active' => $active]) @if ($active) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
        </div></div>
    </nav>
@endif
@push('meta')
    <script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $ld], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endpush
