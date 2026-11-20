@extends('layouts.public')
@section('title', 'Direktori Polsek')
@section('description', 'Alamat, kontak, wilayah pelayanan, dan petunjuk arah Polsek jajaran '.setting('site_name', 'Polres TTS').'.')

@section('content')
    <x-site.page-hero title="Direktori Polsek" :crumbs="['Profil' => null, 'Direktori Polsek' => null]"
                      subtitle="Temukan Polsek terdekat beserta alamat, kontak, dan wilayah pelayanannya." />
    <section class="section">
        <div class="container">
            <form method="GET" class="filter-bar" role="search">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="search" name="q" id="polsekSearch" value="{{ $q }}" class="form-control" placeholder="Cari nama Polsek..." aria-label="Cari nama Polsek" autocomplete="off">
                    <button class="btn btn-navy">Cari</button>
                </div>
            </form>

            @if ($polseks->isEmpty())
                <x-site.empty message="{{ $q ? 'Polsek dengan nama tersebut tidak ditemukan.' : 'Belum ada data Polsek.' }}" icon="bi-geo-alt" />
            @else
                <div class="row g-4">
                    @foreach ($polseks as $p)
                        @php $kapolsek = $p->officials->first(); @endphp
                        <div class="col-md-6 col-lg-4" data-polsek="{{ Str::lower($p->name) }}">
                            <div class="card-clean">
                                <div class="card-media"><img src="{{ thumb_url($p->foto) }}" alt="" loading="lazy"></div>
                                <div class="card-body-clean">
                                    <h2 class="card-title-clean h5">{{ $p->name }}</h2>
                                    @if ($kapolsek)<div class="small text-muted mb-2"><i class="bi bi-person-badge me-1"></i>{{ $kapolsek->jabatan }}: {{ $kapolsek->namaLengkap() }}</div>@endif
                                    <ul class="list-unstyled small mb-3">
                                        <li class="mb-1"><i class="bi bi-geo-alt text-warning me-2"></i>{{ $p->alamat ?: 'Alamat belum tersedia' }}</li>
                                        <li class="mb-1"><i class="bi bi-telephone text-warning me-2"></i>@if ($p->telepon)<a href="{{ tel_link($p->telepon) }}">{{ $p->telepon }}</a>@else Telepon belum tersedia @endif</li>
                                        @if ($p->wilayah_pelayanan)<li><i class="bi bi-map text-warning me-2"></i>{{ Str::limit($p->wilayah_pelayanan, 120) }}</li>@endif
                                    </ul>
                                    <div class="d-flex gap-2 mt-auto">
                                        <a href="{{ $p->directionsUrl() }}" target="_blank" rel="noopener" class="btn btn-navy btn-sm flex-grow-1"><i class="bi bi-sign-turn-right me-1"></i>Petunjuk Arah</a>
                                        <a href="{{ route('site.units.show', $p->slug) }}" class="btn btn-outline-navy btn-sm">Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <x-site.empty id="polsekEmpty" class="d-none mt-3" message="Polsek dengan nama tersebut tidak ditemukan." icon="bi-geo-alt" />
            @endif
        </div>
    </section>
@endsection
