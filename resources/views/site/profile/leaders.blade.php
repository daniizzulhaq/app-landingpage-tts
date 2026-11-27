@extends('layouts.public')
@section('title', 'Profil Pimpinan')
@section('description', 'Profil Kapolres, Wakapolres, dan pejabat utama '.setting('site_tagline', 'Kepolisian Resor Timor Tengah Selatan').'.')
@section('og_image', $kapolres->fotoUrl())

@section('content')
    <x-site.page-hero title="Profil Pimpinan" :crumbs="['Profil' => null, 'Profil Pimpinan' => null]" />

    {{-- Kapolres + sambutan --}}
    <section class="section">
        <div class="container">
            <div class="row g-5 align-items-start">
                <div class="col-md-5 col-lg-4">
                    <div class="leader-card">
                        <div class="leader-photo"><img src="{{ $kapolres->fotoUrl() }}" @class(['kapolres-photo-original' => $kapolres->fotoUrl() === asset(config('kapolres.foto'))]) alt="{{ $kapolres->namaLengkap() }}" width="1280" height="1280" loading="lazy"></div>
                        <div class="info">
                            <div class="jabatan">{{ $kapolres?->jabatan ?? 'Kapolres' }}</div>
                            <div class="nama">{{ $kapolres?->nama ?? '(diisi admin)' }}</div>
                            @if ($kapolres?->pangkat)<div class="small text-muted">{{ $kapolres->pangkat }}</div>@endif
                        </div>
                    </div>
                </div>
                <div class="col-md-7 col-lg-8">
                    <div class="leader-message">
                    <span class="eyebrow mb-3">MENGABDI DENGAN HATI</span>
                    <h2 class="section-title mb-4">{{ $sambutan?->judul ?? 'Sambutan Kapolres' }}</h2>
                    @if ($sambutan?->isi)
                        <div class="prose">{!! $sambutan->isi !!}</div>
                    @else
                        <x-site.empty message="Sambutan belum tersedia." />
                    @endif
                    @if ($kapolres?->tugas)
                        <h3 class="h5 mt-4">Profil Singkat</h3>
                        <div class="prose">{!! $kapolres->tugas !!}</div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Wakapolres --}}
    <section class="section section-alt">
        <div class="container">
            <div class="row g-5 align-items-start">
                <div class="col-md-5 col-lg-4 order-md-2">
                    <div class="leader-card">
                        <img src="{{ media_url($wakapolres?->foto, 'img/avatar.svg') }}" alt="Foto Wakapolres" loading="lazy">
                        <div class="info">
                            <div class="jabatan">{{ $wakapolres?->jabatan ?? 'Wakapolres' }}</div>
                            <div class="nama">{{ $wakapolres?->nama ?? '(diisi admin)' }}</div>
                            @if ($wakapolres?->pangkat)<div class="small text-muted">{{ $wakapolres->pangkat }}</div>@endif
                        </div>
                    </div>
                </div>
                <div class="col-md-7 col-lg-8 order-md-1">
                    <div class="leader-message">
                    <span class="eyebrow mb-3">KEPEMIMPINAN & PENGABDIAN</span>
                    <h2 class="section-title mb-4">Profil Wakapolres</h2>
                    @if ($wakapolres?->tugas)
                        <div class="prose">{!! $wakapolres->tugas !!}</div>
                    @else
                        <x-site.empty message="Profil belum tersedia." />
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Pejabat utama --}}
    <section class="section">
        <div class="container">
            <x-site.section-head title="Pejabat Utama" :link="route('site.structure')" linkText="Struktur organisasi" />
            @if ($pejabat->isEmpty())
                <x-site.empty message="Belum ada data pejabat." />
            @else
                <div class="row g-3">
                    @foreach ($pejabat as $o)
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <a href="{{ route('site.units.show', $o->unit->slug) }}" class="official-mini text-decoration-none">
                                <img src="{{ thumb_url($o->foto, 'img/avatar.svg') }}" alt="" loading="lazy">
                                <div class="min-w-0"><div class="j">{{ $o->jabatan }}</div><div class="n">{{ $o->namaLengkap() }}</div></div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
