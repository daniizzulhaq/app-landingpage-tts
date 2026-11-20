@extends('layouts.admin')
@php
    $label = App\Models\Post::typeLabel($type);
    $super = auth()->user()->isSuperAdmin();
@endphp
@section('title', ($post->exists ? 'Ubah ' : 'Tambah ').$label)

@section('content')
    <x-admin.page-header :title="($post->exists ? 'Ubah ' : 'Tambah ').$label" :back="route('admin.posts.index', $type)">
        @if ($post->exists)<x-admin.status :model="$post" />@endif
    </x-admin.page-header>

    @if ($post->status === 'rejected' && $post->catatan_revisi)
        <div class="alert alert-warning">
            <div class="fw-semibold"><i class="bi bi-arrow-return-left me-1"></i>Dikembalikan oleh Super Admin. Catatan revisi:</div>
            <div class="mt-1" style="white-space:pre-line">{{ $post->catatan_revisi }}</div>
        </div>
    @endif

    <form method="POST" enctype="multipart/form-data"
          action="{{ $post->exists ? route('admin.posts.update', [$type, $post]) : route('admin.posts.store', $type) }}">
        @csrf
        @if ($post->exists) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card mb-3"><div class="card-body">
                    <x-admin.input name="judul" label="Judul" :value="$post->judul" required maxlength="200" />
                    <x-admin.textarea name="ringkasan" label="Ringkasan" :value="$post->ringkasan" rows="2" maxlength="500"
                                      help="Tampil di kartu daftar dan deskripsi saat dibagikan. Jika kosong, diambil dari isi." />
                    <x-admin.editor name="isi" label="Isi" :value="$post->isi" required height="380" />
                </div></div>

                <div class="card"><div class="card-header">Galeri Foto</div><div class="card-body">
                    @if ($post->exists && $post->images->isNotEmpty())
                        <div class="photo-grid mb-3">
                            @foreach ($post->images as $img)
                                <div class="photo-item">
                                    <img src="{{ thumb_url($img->path) }}" alt="" loading="lazy">
                                    <div class="p-2">
                                        <input type="text" name="captions[{{ $img->id }}]" value="{{ $img->caption }}" class="form-control form-control-sm mb-1" placeholder="Keterangan" aria-label="Keterangan foto">
                                        <div class="form-check small">
                                            <input class="form-check-input" type="checkbox" name="hapus_images[]" value="{{ $img->id }}" id="hi{{ $img->id }}">
                                            <label class="form-check-label text-danger" for="hi{{ $img->id }}">Hapus</label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <label for="f_images" class="form-label">Tambah foto (bisa pilih banyak)</label>
                    <input type="file" name="images[]" id="f_images" class="form-control" accept="image/jpeg,image/png,image/webp" multiple>
                    <div class="form-text">JPG/PNG/WebP, maks 5 MB per foto, maks 30 foto sekali unggah. Foto otomatis diperkecil & dikompres.</div>
                </div></div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-3"><div class="card-body">
                    <x-admin.select name="category_id" label="Kategori" :options="$categories" :value="$post->category_id" placeholder="— Tanpa kategori —" />
                    @if ($super)
                        <x-admin.select name="unit_id" label="Unit" :options="$units" :value="$post->unit_id" placeholder="— Polres (umum) —" />
                    @else
                        <div class="mb-3">
                            <label class="form-label">Unit</label>
                            <select class="form-select" disabled aria-label="Unit"><option>{{ auth()->user()->unit?->name }}</option></select>
                            <div class="form-text"><i class="bi bi-lock"></i> Terkunci ke unit Anda.</div>
                        </div>
                    @endif

                    @if ($type === 'kegiatan' || $type === 'berita')
                        <x-admin.input type="date" name="tanggal_kegiatan" :label="$type === 'kegiatan' ? 'Tanggal kegiatan' : 'Tanggal peristiwa (opsional)'"
                                       :value="optional($post->tanggal_kegiatan)->format('Y-m-d')" :required="$type === 'kegiatan'" />
                        <x-admin.input name="lokasi" label="Lokasi" :value="$post->lokasi" />
                    @endif

                    @if ($super)
                        <x-admin.input type="datetime-local" name="published_at" label="Tanggal tayang (opsional)"
                                       :value="optional($post->published_at)->format('Y-m-d\TH:i')" help="Kosongkan untuk tayang sekarang. Isi tanggal mendatang untuk menjadwalkan." />
                        <x-admin.checkbox name="is_penting" label="Tandai penting (tampil di strip pengumuman beranda)" :checked="$post->is_penting" />
                    @endif
                </div></div>

                <div class="card"><div class="card-body">
                    <x-admin.image name="thumbnail" label="Gambar utama" :current="$post->thumbnail" />
                    <x-admin.file name="lampiran" :label="$type === 'edukasi' ? 'Poster / infografis / PDF' : 'Lampiran (poster/PDF, opsional)'" :current="$post->lampiran" />
                </div></div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.posts.index', $type) }}" class="btn btn-light">Batal</a>
            <button type="submit" name="action" value="draft" class="btn btn-outline-primary"><i class="bi bi-save me-1"></i>Simpan Draft</button>
            <button type="submit" name="action" value="submit" class="btn btn-primary">
                @if ($canPublish)
                    <i class="bi bi-send-check me-1"></i>Simpan & Tayangkan
                @else
                    <i class="bi bi-send me-1"></i>Kirim untuk Persetujuan
                @endif
            </button>
        </div>
    </form>
@endsection
