@extends('layouts.admin')
@section('title', $slider->exists ? 'Ubah Slider' : 'Tambah Slider')

@section('content')
    <x-admin.page-header :title="$slider->exists ? 'Ubah Slider' : 'Tambah Slider'" :back="route('admin.sliders.index')" />
    <div class="card" style="max-width:820px"><div class="card-body">
        <form method="POST" enctype="multipart/form-data" action="{{ $slider->exists ? route('admin.sliders.update', $slider) : route('admin.sliders.store') }}">
            @csrf
            @if ($slider->exists) @method('PUT') @endif
            <x-admin.input name="judul" label="Judul" :value="$slider->judul" required />
            <x-admin.textarea name="subjudul" label="Subjudul" :value="$slider->subjudul" rows="2" />
            <x-admin.input name="link" label="Tautan tombol (opsional)" :value="$slider->link" help="Contoh: /layanan atau https://..." />
            <x-admin.image name="gambar" label="Gambar banner" :current="$slider->gambar" :removable="false" :required="! $slider->exists"
                           help="Disarankan rasio lebar 16:9, minimal 1600px. JPG/PNG/WebP maks 5 MB." />
            <div class="row">
                <x-admin.input type="number" name="urutan" label="Urutan" :value="$slider->urutan ?? 0" min="0" col="col-md-4 mb-3" />
                <div class="col-md-8 pt-md-4"><x-admin.checkbox name="is_active" label="Tampilkan di beranda" :checked="$slider->is_active" /></div>
            </div>
            <x-admin.form-actions :cancel="route('admin.sliders.index')" />
        </form>
    </div></div>
@endsection
