@extends('layouts.admin')
@section('title', 'Ubah Halaman')

@section('content')
    <x-admin.page-header :title="'Ubah: '.$page->judul" :back="route('admin.pages.index')" />
    <form method="POST" action="{{ route('admin.pages.update', $page) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card"><div class="card-body">
                    <x-admin.input name="judul" label="Judul" :value="$page->judul" required />
                    <x-admin.editor name="isi" label="Isi halaman" :value="$page->isi" height="380" />
                </div></div>
            </div>
            <div class="col-lg-4">
                <div class="card"><div class="card-body">
                    <x-admin.image name="gambar" label="Gambar pendukung (opsional)" :current="$page->gambar" />
                    @if ($page->key === 'sambutan_kapolres')
                        <div class="small text-muted">Foto Kapolres diambil dari menu <b>Pejabat</b> (level Kapolres).</div>
                    @endif
                </div></div>
            </div>
        </div>
        <x-admin.form-actions :cancel="route('admin.pages.index')" />
    </form>
@endsection
