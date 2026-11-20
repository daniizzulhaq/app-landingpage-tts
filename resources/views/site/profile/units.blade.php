@extends('layouts.public')
@section('title', 'Satuan Kerja')
@section('description', 'Daftar bagian, satuan, dan seksi di lingkungan '.setting('site_tagline', 'Kepolisian Resor Timor Tengah Selatan').'.')

@section('content')
    <x-site.page-hero title="Satuan Kerja" :crumbs="['Profil' => null, 'Satuan Kerja' => null]"
                      subtitle="Bagian, satuan, dan seksi beserta tugas dan layanannya." />
    <section class="section">
        <div class="container">
            @php $labels = ['bag' => 'Bagian', 'sat' => 'Satuan', 'sie' => 'Seksi']; @endphp
            @foreach ($groups as $type => $units)
                <div class="mb-5">
                    <x-site.section-head :title="$labels[$type]" />
                    @if ($units->isEmpty())
                        <x-site.empty message="Belum ada data." />
                    @else
                        <div class="row g-3">
                            @foreach ($units as $unit)
                                <div class="col-sm-6 col-lg-4">
                                    <a href="{{ route('site.units.show', $unit->slug) }}" class="quick-card">
                                        <span class="quick-icon"><i class="bi bi-diagram-3"></i></span>
                                        <span class="min-w-0"><span class="t d-block">{{ $unit->name }}</span><span class="d">{{ $unit->deskripsi }}</span></span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
            <div class="info-box d-flex flex-wrap align-items-center gap-3">
                <i class="bi bi-geo-alt fs-3 text-warning"></i>
                <div class="flex-grow-1"><div class="fw-bold text-navy">Mencari Polsek terdekat?</div><div class="small text-muted">Lihat alamat, kontak, dan wilayah pelayanan setiap Polsek.</div></div>
                <a href="{{ route('site.polsek') }}" class="btn btn-navy">Direktori Polsek</a>
            </div>
        </div>
    </section>
@endsection
