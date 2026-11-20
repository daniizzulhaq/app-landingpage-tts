@extends('layouts.public')
@section('title', 'Halaman tidak ditemukan')
@push('meta')<meta name="robots" content="noindex">@endpush

@section('content')
    <section class="section">
        <div class="container text-center" style="max-width:640px"><div class="detail-panel">
            <span class="eyebrow mb-3">POLRES TIMOR TENGAH SELATAN</span>
            <div class="display-1 fw-bold mb-3" style="color:var(--brass)">404</div>
            <h1 class="h3">Halaman tidak ditemukan</h1>
            <p class="text-muted">Halaman yang Anda cari mungkin sudah dipindahkan atau tidak tersedia.</p>
            <form action="{{ route('site.search') }}" method="GET" class="my-4" role="search">
                <div class="input-group">
                    <input type="search" name="q" class="form-control" placeholder="Cari di situs ini..." aria-label="Kata kunci" minlength="2" required>
                    <button class="btn btn-navy">Cari</button>
                </div>
            </form>
            <a href="{{ route('home') }}" class="btn btn-gold">Kembali ke Beranda</a>
            </div>
        </div>
    </section>
@endsection
