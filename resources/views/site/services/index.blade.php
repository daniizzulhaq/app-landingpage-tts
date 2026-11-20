@extends('layouts.public')
@section('title', 'Informasi Layanan')
@section('description', 'Persyaratan, prosedur, biaya, jadwal, dan lokasi layanan kepolisian di '.setting('site_name', 'Polres TTS').'.')

@section('content')
    <x-site.page-hero title="Informasi Layanan" :crumbs="['Layanan' => null, 'Informasi Layanan' => null]"
                      subtitle="Pilih layanan untuk melihat persyaratan, prosedur, biaya, jadwal, dan formulir." />
    <section class="section">
        <div class="container">
            <div class="section-intro">
                <div>
                    <span class="eyebrow">SATU PORTAL, BERAGAM LAYANAN</span>
                    <h2>Pelayanan yang lebih mudah<span class="heading-dot">.</span></h2>
                    <p>Kenali persyaratan dan alur pelayanan sebelum Anda datang. Pilih layanan yang Anda butuhkan.</p>
                </div>
                <a href="{{ route('site.faq') }}" class="home-text-link">Punya pertanyaan? <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
            </div>
            @if ($services->isEmpty())
                <x-site.empty message="Belum ada informasi layanan." />
            @else
                <div class="row g-4 service-directory-grid">
                    @foreach ($services as $s)
                        <div class="col-md-6 col-lg-4">
                            <a href="{{ route('site.services.show', $s->slug) }}" class="service-directory-card">
                                <div class="directory-card-top">
                                    <span class="service-icon"><i class="bi {{ $s->ikon ?: 'bi-shield-check' }}" aria-hidden="true"></i></span>
                                    <span class="directory-card-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <h3>{{ $s->nama }}</h3>
                                <p>{{ $s->deskripsi ?: 'Lihat persyaratan, prosedur, dan informasi pelayanan.' }}</p>
                                @if ($s->unit)<span class="directory-card-unit"><i class="bi bi-diagram-3" aria-hidden="true"></i>{{ $s->unit->name }}</span>@endif
                                <span class="directory-card-footer">Lihat informasi layanan <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="call-110 mt-5">
                <span class="num">110</span>
                <div class="flex-grow-1"><div class="fw-bold fs-5 text-white">Butuh bantuan darurat?</div><div class="text-white-50">Hubungi layanan darurat kepolisian 24 jam, bebas pulsa.</div></div>
                <a href="tel:110" class="btn btn-gold btn-lg"><i class="bi bi-telephone-fill me-1"></i>Telepon 110</a>
            </div>
        </div>
    </section>
@endsection
