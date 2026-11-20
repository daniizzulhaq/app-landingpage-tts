@extends('layouts.public')
@section('title', 'PPID — Informasi Publik')
@section('description', 'Pejabat Pengelola Informasi dan Dokumentasi (PPID) '.setting('site_name', 'Polres TTS').': dokumen informasi publik dan permohonan informasi.')

@section('content')
    <x-site.page-hero title="PPID" :crumbs="['Informasi Publik' => null, 'PPID' => null]"
                      subtitle="Pejabat Pengelola Informasi dan Dokumentasi — layanan keterbukaan informasi publik." />
    <section class="section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <x-site.section-head title="Dokumen Informasi Publik" />
                    <form method="GET" class="filter-bar" role="search">
                        <div class="row g-2">
                            <div class="col-12 col-md"><input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari judul dokumen..." aria-label="Cari dokumen"></div>
                            @if ($years->isNotEmpty())
                                <div class="col-7 col-md-3">
                                    <select name="tahun" class="form-select" aria-label="Tahun">
                                        <option value="">Semua tahun</option>
                                        @foreach ($years as $y)<option value="{{ $y }}" @selected(request('tahun') == $y)>{{ $y }}</option>@endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="col-5 col-md-auto"><button class="btn btn-navy w-100">Cari</button></div>
                        </div>
                    </form>

                    @php $hasDocs = $groups->contains(fn ($g) => $g['items']->isNotEmpty()); @endphp
                    @if (! $hasDocs)
                        <x-site.empty message="Belum ada dokumen." icon="bi-folder2-open" />
                    @else
                        @foreach ($groups as $key => $g)
                            @continue($g['items']->isEmpty())
                            <h3 class="h6 text-uppercase text-muted mt-4 mb-3" style="letter-spacing:.06em">{{ $g['label'] }}</h3>
                            @foreach ($g['items'] as $d)
                                <div class="file-link flex-wrap">
                                    <i class="bi {{ $d->extension() === 'PDF' ? 'bi-file-earmark-pdf' : 'bi-file-earmark' }}"></i>
                                    <div class="min-w-0 flex-grow-1">
                                        <div class="fw-semibold">{{ $d->judul }}</div>
                                        <div class="small text-muted">{{ $d->tahun ? 'Tahun '.$d->tahun.' · ' : '' }}{{ $d->extension() }} · {{ $d->sizeLabel() }}</div>
                                        @if ($d->deskripsi)<div class="small text-muted">{{ $d->deskripsi }}</div>@endif
                                    </div>
                                    <a href="{{ media_url($d->path) }}" class="btn btn-sm btn-navy" download target="_blank" rel="noopener"><i class="bi bi-download me-1"></i>Unduh</a>
                                </div>
                            @endforeach
                        @endforeach
                    @endif

                    <div class="mt-5">
                        <x-site.section-head title="Standar Pelayanan Informasi" />
                        @if (setting('ppid_standar_pelayanan'))
                            <div class="prose">{!! setting('ppid_standar_pelayanan') !!}</div>
                        @else
                            <x-site.empty message="Standar pelayanan belum tersedia." />
                        @endif
                    </div>
                </div>

                <aside class="col-lg-4" id="permohonan">
                    <div class="sidebar-card sticky-side">
                        <h2>Form Permohonan Informasi</h2>
                        @if (session('ppid_success'))
                            <div class="alert alert-success small" role="status">
                                <i class="bi bi-check-circle me-1"></i>Permohonan Anda telah kami terima. Petugas akan menghubungi melalui kontak yang Anda cantumkan.
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger small" role="alert">Periksa kembali isian Anda.</div>
                        @endif
                        <form method="POST" action="{{ route('site.ppid.store') }}" novalidate>
                            @csrf
                            {{-- Honeypot anti-spam: jangan diisi --}}
                            <div class="visually-hidden" aria-hidden="true">
                                <label for="website">Website</label>
                                <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                            </div>
                            <div class="mb-3">
                                <label for="nama" class="form-label small fw-semibold">Nama lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" required maxlength="150" autocomplete="name">
                                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="kontak" class="form-label small fw-semibold">Nomor HP / email <span class="text-danger">*</span></label>
                                <input type="text" name="kontak" id="kontak" value="{{ old('kontak') }}" class="form-control @error('kontak') is-invalid @enderror" required maxlength="150">
                                @error('kontak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="informasi_diminta" class="form-label small fw-semibold">Informasi yang diminta <span class="text-danger">*</span></label>
                                <textarea name="informasi_diminta" id="informasi_diminta" rows="4" class="form-control @error('informasi_diminta') is-invalid @enderror" required maxlength="3000">{{ old('informasi_diminta') }}</textarea>
                                @error('informasi_diminta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="tujuan" class="form-label small fw-semibold">Tujuan penggunaan informasi <span class="text-danger">*</span></label>
                                <textarea name="tujuan" id="tujuan" rows="3" class="form-control @error('tujuan') is-invalid @enderror" required maxlength="2000">{{ old('tujuan') }}</textarea>
                                @error('tujuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <button class="btn btn-gold w-100"><i class="bi bi-send me-1"></i>Kirim Permohonan</button>
                            <p class="small text-muted mt-2 mb-0">Data Anda hanya digunakan untuk menindaklanjuti permohonan.</p>
                        </form>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
