@extends('layouts.public')
@section('title', $service->nama)
@section('description', $service->deskripsi ?: 'Persyaratan dan prosedur '.$service->nama.' di '.setting('site_name', 'Polres TTS').'.')

@section('content')
    @php
        $syarat = App\Models\Service::lines($service->persyaratan);
        $langkah = App\Models\Service::lines($service->prosedur);
    @endphp
    <x-site.page-hero :title="$service->nama" :subtitle="$service->deskripsi"
                      :crumbs="['Layanan' => null, 'Informasi Layanan' => route('site.services.index'), $service->nama => null]" />
    <section class="section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <section class="detail-panel" aria-labelledby="requirements-title">
                    <div class="detail-section-head"><span class="section-index">01</span><h2 class="section-title" id="requirements-title">Persyaratan</h2></div>
                    @if ($syarat)
                        <ul class="check-list">
                            @foreach ($syarat as $item)<li>{{ $item }}</li>@endforeach
                        </ul>
                    @else
                        <x-site.empty message="Persyaratan belum tersedia." />
                    @endif

                    </section>
                    <section class="detail-panel" aria-labelledby="procedure-title">
                    <div class="detail-section-head"><span class="section-index">02</span><h2 class="section-title" id="procedure-title">Prosedur</h2></div>
                    @if ($langkah)
                        <ol class="steps">
                            @foreach ($langkah as $item)<li>{{ $item }}</li>@endforeach
                        </ol>
                    @else
                        <x-site.empty message="Prosedur belum tersedia." />
                    @endif

                    </section>
                    <section class="detail-panel" aria-labelledby="downloads-title">
                    <div class="detail-section-head"><span class="section-index">03</span><h2 class="section-title" id="downloads-title">Unduhan Formulir</h2></div>
                    @forelse ($service->files as $f)
                        <a href="{{ media_url($f->path) }}" class="file-link" target="_blank" rel="noopener" download>
                            <i class="bi bi-file-earmark-arrow-down"></i>
                            <span class="fw-semibold">{{ $f->nama }}</span>
                            <span class="ext">{{ strtoupper(pathinfo($f->path, PATHINFO_EXTENSION)) }}</span>
                        </a>
                    @empty
                        <x-site.empty message="Tidak ada formulir untuk layanan ini." icon="bi-file-earmark" />
                    @endforelse
                    </section>

                    <div class="mt-5"><x-site.share :url="url()->current()" :title="$service->nama" /></div>
                </div>

                <aside class="col-lg-4">
                    <div class="sticky-side">
                        <div class="service-summary mb-4">
                            <div class="summary-heading"><i class="bi {{ $service->ikon ?: 'bi-shield-check' }}" aria-hidden="true"></i><div><span class="eyebrow">SEBELUM ANDA DATANG</span><h2>Informasi pelayanan</h2></div></div>
                            <div class="info-box">
                            <dl class="mb-0">
                                <dt><i class="bi bi-cash-coin me-1"></i>Biaya</dt><dd style="white-space:pre-line">{{ $service->biaya ?: '-' }}</dd>
                                <dt><i class="bi bi-clock me-1"></i>Jadwal</dt><dd style="white-space:pre-line">{{ $service->jadwal ?: '-' }}</dd>
                                <dt><i class="bi bi-geo-alt me-1"></i>Lokasi</dt><dd style="white-space:pre-line">{{ $service->lokasi ?: '-' }}</dd>
                                @if ($service->unit)
                                    <dt><i class="bi bi-diagram-3 me-1"></i>Unit Pelaksana</dt>
                                    <dd><a href="{{ route('site.units.show', $service->unit->slug) }}">{{ $service->unit->name }}</a></dd>
                                @endif
                            </dl>
                            </div>
                        </div>
                        @if ($others->isNotEmpty())
                            <div class="sidebar-card">
                                <h2>Layanan Lainnya</h2>
                                <ul class="list-unstyled mb-0">
                                    @foreach ($others as $o)
                                        <li class="py-2 border-bottom"><a href="{{ route('site.services.show', $o->slug) }}" class="text-decoration-none fw-semibold"><i class="bi {{ $o->ikon }} me-2 text-warning"></i>{{ $o->nama }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="help-panel mt-4"><i class="bi bi-chat-square-text" aria-hidden="true"></i><div><strong>Butuh penjelasan lebih lanjut?</strong><p>Temukan jawaban seputar pelayanan atau hubungi petugas melalui kanal resmi.</p><a href="{{ route('site.faq') }}" class="home-text-link">Pertanyaan umum <i class="bi bi-arrow-right" aria-hidden="true"></i></a></div></div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
