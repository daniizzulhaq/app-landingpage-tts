@extends('layouts.public')
@section('title', 'Struktur Organisasi')
@section('description', 'Bagan struktur organisasi '.setting('site_tagline', 'Kepolisian Resor Timor Tengah Selatan').'.')

@section('content')
    <x-site.page-hero title="Struktur Organisasi" :crumbs="['Profil' => null, 'Struktur Organisasi' => null]" />
    <section class="section">
        <div class="container">
            {{-- Pimpinan --}}
            <div class="org-top mb-4">
                <div class="org-node lead">
                    <img src="{{ $kapolres->fotoUrl() }}" alt="{{ $kapolres->namaLengkap() }}" loading="lazy">
                    <div class="role">{{ $kapolres?->jabatan ?? 'Kapolres' }}</div>
                    <div class="who">{{ $kapolres?->namaLengkap() ?? '(diisi admin)' }}</div>
                </div>
                <div class="connector d-none d-lg-block"></div>
                <div class="org-node lead mt-3 mt-lg-0">
                    <img src="{{ thumb_url($wakapolres?->foto, 'img/avatar.svg') }}" alt="" loading="lazy">
                    <div class="role">{{ $wakapolres?->jabatan ?? 'Wakapolres' }}</div>
                    <div class="who">{{ $wakapolres?->namaLengkap() ?? '(diisi admin)' }}</div>
                </div>
                <div class="connector d-none d-lg-block"></div>
            </div>

            {{-- Unit per kelompok --}}
            <ul class="org-tree">
                <li>
                    <ul>
                        @foreach ($groups as $type => $group)
                            @continue($group['units']->isEmpty())
                            <li>
                                <span class="org-group-title">{{ $group['label'] }}</span>
                                <div class="org-units">
                                    @foreach ($group['units'] as $unit)
                                        @php $head = $unit->officials->first(); @endphp
                                        <div class="org-node">
                                            <img src="{{ thumb_url($head?->foto, 'img/avatar.svg') }}" alt="" loading="lazy">
                                            <a href="{{ route('site.units.show', $unit->slug) }}" class="min-w-0">
                                                <div class="role">{{ $head?->jabatan ?? $unit->name }}</div>
                                                <div class="who text-truncate">{{ $head?->namaLengkap() ?? $unit->name }}</div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>

            @if ($bagan)
                <div class="mt-5">
                    <x-site.section-head title="Bagan Resmi" subtitle="Klik gambar untuk memperbesar." />
                    <a href="{{ media_url($bagan) }}" data-lightbox="bagan" data-caption="Bagan struktur organisasi" class="d-block article-cover border">
                        <img src="{{ media_url($bagan) }}" alt="Bagan struktur organisasi" loading="lazy" style="max-height:none;object-fit:contain;background:#fff">
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection
