@extends('layouts.public')
@section('title', 'Pengaduan dan Bantuan')
@section('description', 'Kanal pengaduan resmi, layanan darurat 110, dan kontak pelayanan '.setting('site_name', 'Polres TTS').'.')

@section('content')
    <x-site.page-hero title="Pengaduan dan Bantuan" :crumbs="['Layanan' => null, 'Pengaduan dan Bantuan' => null]"
                      subtitle="Sampaikan laporan atau pengaduan melalui kanal resmi berikut." />
    <section class="section">
        <div class="container">
            <div class="call-110 mb-5">
                <span class="num">110</span>
                <div class="flex-grow-1">
                    <div class="fw-bold fs-5 text-white">Layanan Darurat Kepolisian</div>
                    <div class="text-white-50">Untuk kejadian darurat, kejahatan yang sedang berlangsung, atau kecelakaan. Aktif 24 jam dan bebas pulsa.</div>
                </div>
                <a href="tel:110" class="btn btn-gold btn-lg"><i class="bi bi-telephone-fill me-1"></i>Telepon 110</a>
            </div>

            <x-site.section-head title="Kanal Pengaduan Resmi" />
            @if ($channels->isEmpty())
                <x-site.empty message="Belum ada kanal pengaduan." />
            @else
                <div class="row g-4 mb-5">
                    @foreach ($channels as $c)
                        <div class="col-md-6 col-lg-4">
                            <div class="card-clean channel-card">
                                <span class="ic"><i class="bi {{ $c->ikon }}"></i></span>
                                <h3 class="h6 fw-bold">{{ $c->nama }}</h3>
                                @if ($c->deskripsi)<p class="small text-muted">{{ $c->deskripsi }}</p>@endif
                                @if ($c->url)
                                    <a href="{{ $c->url }}" class="btn btn-outline-navy btn-sm mt-auto align-self-start" @if (str_starts_with($c->url, 'http')) target="_blank" rel="noopener" @endif>
                                        {{ str_starts_with($c->url, 'tel:') ? 'Telepon' : (str_starts_with($c->url, 'mailto:') ? 'Kirim email' : 'Buka kanal') }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <x-site.section-head title="Kontak Pelayanan" />
            @include('site.partials.contact-block')
        </div>
    </section>
@endsection
