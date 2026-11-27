<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Dashboard') · Admin {{ setting('site_name', 'Polres TTS') }}</title>
    <link rel="icon" href="{{ media_url(setting('logo'), 'img/logo-polda-ntt.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @stack('styles')
    <link href="{{ asset('css/admin.css') }}?v=1" rel="stylesheet">
</head>
<body class="admin">
@php
    $user = auth()->user();
    $isSuper = $user->isSuperAdmin();
    $menu = [
        ['Utama', [
            ['admin.dashboard', 'bi-speedometer2', 'Dashboard', true],
            ['admin.approval.index', 'bi-hourglass-split', 'Menunggu Approval', $isSuper, 'approval'],
        ]],
        ['Konten', [
            ['admin.posts.index', 'bi-newspaper', 'Berita', true, null, ['type' => 'berita']],
            ['admin.posts.index', 'bi-camera-reels', 'Kegiatan', true, null, ['type' => 'kegiatan']],
            ['admin.posts.index', 'bi-megaphone', 'Pengumuman', $isSuper, null, ['type' => 'pengumuman']],
            ['admin.posts.index', 'bi-lightbulb', 'Edukasi', true, null, ['type' => 'edukasi']],
            ['admin.categories.index', 'bi-tags', 'Kategori', $isSuper],
            ['admin.albums.index', 'bi-images', 'Album Foto', true],
            ['admin.videos.index', 'bi-youtube', 'Video', $isSuper],
            ['admin.agendas.index', 'bi-calendar-event', 'Agenda', true],
        ]],
        ['Profil & Layanan', [
            ['admin.units.index', 'bi-diagram-3', $isSuper ? 'Unit' : 'Profil Unit', true],
            ['admin.officials.index', 'bi-person-badge', 'Pejabat', $isSuper],
            ['admin.services.index', 'bi-card-checklist', 'Layanan', $isSuper],
            ['admin.complaint-channels.index', 'bi-headset', 'Kanal Pengaduan', $isSuper],
            ['admin.faqs.index', 'bi-question-circle', 'FAQ', $isSuper],
        ]],
        ['Informasi Publik', [
            ['admin.documents.index', 'bi-folder2-open', 'Dokumen PPID', $isSuper],
            ['admin.information-requests.index', 'bi-envelope-paper', 'Permohonan Informasi', $isSuper, 'requests'],
        ]],
        ['Tampilan & Sistem', [
            ['admin.pages.index', 'bi-file-earmark-text', 'Halaman Statis', $isSuper],
            ['admin.sliders.index', 'bi-collection', 'Slider', $isSuper],
            ['admin.settings.edit', 'bi-gear', 'Pengaturan', $isSuper],
            ['admin.users.index', 'bi-people', 'Pengguna', $isSuper],
        ]],
    ];
    $badges = $isSuper ? [
        'approval' => \App\Models\Post::where('status', 'pending')->count()
            + \App\Models\Album::where('status', 'pending')->count()
            + \App\Models\Agenda::where('status', 'pending')->count(),
        'requests' => \App\Models\InformationRequest::where('status', 'baru')->count(),
    ] : [];
@endphp

<nav id="sidebar" class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" aria-label="Menu admin">
    <div class="sidebar-brand">
        <img src="{{ media_url(setting('logo'), 'img/logo-polda-ntt.png') }}" alt="" width="36" height="36">
        <div class="lh-sm">
            <div class="fw-semibold">{{ setting('site_name', 'Polres TTS') }}</div>
            <small class="text-white-50">Panel Admin</small>
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#sidebar" aria-label="Tutup"></button>
    </div>
    <div class="sidebar-body">
        @foreach ($menu as [$group, $items])
            @php $visible = collect($items)->filter(fn ($i) => $i[3] && Route::has($i[0])); @endphp
            @if ($visible->isNotEmpty())
                <div class="sidebar-heading">{{ $group }}</div>
                @foreach ($visible as $item)
                    @php
                        $params = $item[5] ?? [];
                        $url = route($item[0], $params);
                        $active = isset($params['type'])
                            ? request()->routeIs('admin.posts.*') && request()->route('type') === $params['type']
                            : ($item[0] === 'admin.dashboard'
                                ? request()->routeIs('admin.dashboard')
                                : request()->routeIs(Str::beforeLast($item[0], '.').'.*'));
                        $count = isset($item[4]) ? ($badges[$item[4]] ?? 0) : 0;
                    @endphp
                    <a href="{{ $url }}" class="sidebar-link {{ $active ? 'active' : '' }}">
                        <i class="bi {{ $item[1] }}"></i><span>{{ $item[2] }}</span>
                        @if ($count)<span class="badge rounded-pill bg-warning text-dark ms-auto">{{ $count }}</span>@endif
                    </a>
                @endforeach
            @endif
        @endforeach
        <div class="sidebar-heading">Akun</div>
        <a href="{{ route('home') }}" target="_blank" class="sidebar-link"><i class="bi bi-box-arrow-up-right"></i><span>Lihat Situs</span></a>
    </div>
</nav>

<div class="admin-main">
    <header class="topbar">
        <button class="btn btn-light d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar" aria-label="Buka menu">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div class="fw-semibold text-truncate">@yield('title', 'Dashboard')</div>
        <div class="dropdown ms-auto">
            <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i>
                <span class="d-none d-sm-inline text-truncate" style="max-width:180px">{{ $user->name }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li class="px-3 py-2 small text-muted">
                    {{ $user->roleLabel() }}@if ($user->unit) · {{ $user->unit->name }}@endif
                </li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('admin.password.edit') }}"><i class="bi bi-key me-2"></i>Ganti Kata Sandi</a></li>
                <li>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                    </form>
                </li>
            </ul>
        </div>
    </header>

    <main class="admin-content">
        @include('admin.partials.flash')
        @yield('content')
    </main>
</div>

{{-- Modal konfirmasi hapus (dipakai semua tombol data-confirm) --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <i class="bi bi-exclamation-triangle text-danger fs-1"></i>
                <p class="mt-2 mb-0" id="confirmMessage">Yakin ingin menghapus data ini?</p>
            </div>
            <div class="modal-footer justify-content-center border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmOk">Ya, lanjutkan</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
<script src="{{ asset('js/admin.js') }}?v=1"></script>
</body>
</html>
