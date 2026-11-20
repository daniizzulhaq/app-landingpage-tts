@extends('layouts.public')
@section('title', 'Kontak dan Lokasi')
@section('description', 'Alamat, peta lokasi, telepon, email, jam pelayanan, dan media sosial '.setting('site_name', 'Polres TTS').'.')

@section('content')
    <x-site.page-hero title="Kontak dan Lokasi" :crumbs="['Informasi Publik' => null, 'Kontak dan Lokasi' => null]" />
    <section class="section">
        <div class="container">
            @include('site.partials.contact-block')
            <div class="call-110 mt-5">
                <span class="num">110</span>
                <div class="flex-grow-1"><div class="fw-bold fs-5 text-white">Keadaan darurat?</div><div class="text-white-50">Hubungi layanan darurat kepolisian 24 jam, bebas pulsa.</div></div>
                <a href="tel:110" class="btn btn-gold btn-lg"><i class="bi bi-telephone-fill me-1"></i>Telepon 110</a>
            </div>
        </div>
    </section>
@endsection
