@extends('layouts.public')
@section('title', $unit->name)
@section('description', $unit->deskripsi ?: $unit->name.' '.setting('site_tagline', 'Polres TTS'))
@section('og_image', $unit->foto ? media_url($unit->foto) : '')

@section('content')
    @php
        $parent = $unit->type === 'polsek' ? ['Direktori Polsek' => route('site.polsek')] : ['Satuan Kerja' => route('site.units.index')];
    @endphp
    <x-site.page-hero :title="$unit->name" :subtitle="$unit->deskripsi" :crumbs="['Profil' => null] + $parent + [$unit->name => null]" />

    <section class="section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <div class="detail-panel unit-overview">
                    @if ($unit->foto)
                        <a href="{{ media_url($unit->foto) }}" data-lightbox="unit" data-caption="{{ $unit->name }}" class="d-block article-cover mb-4">
                            <img src="{{ media_url($unit->foto) }}" alt="{{ $unit->name }}" loading="lazy">
                        </a>
                    @endif

                    <h2 class="section-title mb-3">Tugas Pokok</h2>
                    @if ($unit->tugas_pokok)<div class="prose mb-5">{!! $unit->tugas_pokok !!}</div>@else<x-site.empty class="mb-5" message="Belum tersedia." />@endif

                    <h2 class="section-title mb-3">Layanan</h2>
                    @if ($unit->layanan_unit)<div class="prose mb-5">{!! $unit->layanan_unit !!}</div>@else<x-site.empty class="mb-5" message="Belum tersedia." />@endif

                    <h2 class="section-title mb-4">Pejabat</h2>
                    @if ($unit->officials->isEmpty())
                        <x-site.empty class="mb-5" message="Belum ada data pejabat." />
                    @else
                        <div class="row g-3 mb-5">
                            @foreach ($unit->officials as $o)
                                <div class="col-sm-6">
                                    <div class="official-mini">
                                        <img src="{{ thumb_url($o->foto, 'img/avatar.svg') }}" alt="" loading="lazy">
                                        <div><div class="j">{{ $o->jabatan }}</div><div class="n">{{ $o->namaLengkap() }}</div></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    </div>
                    <div class="mt-5"><x-site.section-head title="Kegiatan Unit" /></div>
                    @if ($kegiatan->isEmpty())
                        <x-site.empty message="Belum ada kegiatan dari unit ini." />
                    @else
                        <div class="row g-4">
                            @foreach ($kegiatan as $p)
                                <div class="col-sm-6"><x-site.post-card :post="$p" show-type /></div>
                            @endforeach
                        </div>
                        <div class="mt-4">{{ $kegiatan->links() }}</div>
                    @endif
                </div>

                <aside class="col-lg-4">
                    <div class="sidebar-card sticky-side">
                        <h2>Kontak</h2>
                        <ul class="contact-list">
                            @if ($unit->alamat)<li><span class="ic"><i class="bi bi-geo-alt"></i></span><div><div class="lbl">Alamat</div><div class="val">{{ $unit->alamat }}</div></div></li>@endif
                            @if ($unit->telepon)<li><span class="ic"><i class="bi bi-telephone"></i></span><div><div class="lbl">Telepon</div><a href="{{ tel_link($unit->telepon) }}" class="val">{{ $unit->telepon }}</a></div></li>@endif
                            @if ($unit->email)<li><span class="ic"><i class="bi bi-envelope"></i></span><div><div class="lbl">Email</div><a href="mailto:{{ $unit->email }}" class="val">{{ $unit->email }}</a></div></li>@endif
                            @if ($unit->wilayah_pelayanan)<li><span class="ic"><i class="bi bi-map"></i></span><div><div class="lbl">Wilayah Pelayanan</div><div class="val" style="white-space:pre-line">{{ $unit->wilayah_pelayanan }}</div></div></li>@endif
                            <li><span class="ic"><i class="bi bi-telephone-fill"></i></span><div><div class="lbl">Darurat</div><a href="tel:110" class="val">110</a></div></li>
                        </ul>
                        @if ($unit->type === 'polsek' || $unit->maps_url)
                            <a href="{{ $unit->directionsUrl() }}" target="_blank" rel="noopener" class="btn btn-navy w-100 mt-3"><i class="bi bi-sign-turn-right me-1"></i>Petunjuk Arah</a>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
