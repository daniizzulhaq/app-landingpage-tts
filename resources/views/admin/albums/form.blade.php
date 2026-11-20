@extends('layouts.admin')
@php $super = auth()->user()->isSuperAdmin(); @endphp
@section('title', $album->exists ? 'Ubah Album' : 'Tambah Album')

@section('content')
    <x-admin.page-header :title="$album->exists ? 'Ubah Album' : 'Tambah Album'" :back="route('admin.albums.index')">
        @if ($album->exists)<x-admin.status :model="$album" />@endif
    </x-admin.page-header>

    @if ($album->status === 'rejected' && $album->catatan_revisi)
        <div class="alert alert-warning">
            <div class="fw-semibold"><i class="bi bi-arrow-return-left me-1"></i>Dikembalikan oleh Super Admin. Catatan revisi:</div>
            <div class="mt-1" style="white-space:pre-line">{{ $album->catatan_revisi }}</div>
        </div>
    @endif

    <div class="row g-3">
        <div class="{{ $album->exists && $album->status === 'pending' && $super ? 'col-lg-8' : 'col-12' }}">
            <form method="POST" enctype="multipart/form-data" id="albumForm"
                  action="{{ $album->exists ? route('admin.albums.update', $album) : route('admin.albums.store') }}">
                @csrf
                @if ($album->exists) @method('PUT') @endif
                <div class="card mb-3"><div class="card-body">
                    <div class="row">
                        <x-admin.input name="judul" label="Judul album" :value="$album->judul" required col="col-md-8 mb-3" />
                        <x-admin.input type="date" name="tanggal" label="Tanggal" :value="optional($album->tanggal)->format('Y-m-d')" col="col-md-4 mb-3" />
                    </div>
                    <x-admin.textarea name="deskripsi" label="Deskripsi" :value="$album->deskripsi" rows="2" />
                    @if ($super)
                        <x-admin.select name="unit_id" label="Unit" :options="$units" :value="$album->unit_id" placeholder="— Polres (umum) —" />
                    @else
                        <div class="mb-3"><label class="form-label">Unit</label>
                            <select class="form-select" disabled aria-label="Unit"><option>{{ auth()->user()->unit?->name }}</option></select>
                            <div class="form-text"><i class="bi bi-lock"></i> Terkunci ke unit Anda.</div>
                        </div>
                    @endif
                    <label for="f_photos" class="form-label">{{ $album->exists ? 'Tambah foto' : 'Unggah foto' }} (bisa pilih banyak sekaligus) @unless ($album->exists)<span class="text-danger">*</span>@endunless</label>
                    <input type="file" name="photos[]" id="f_photos" class="form-control @error('photos') is-invalid @enderror @error('photos.*') is-invalid @enderror"
                           accept="image/jpeg,image/png,image/webp" multiple @unless ($album->exists) required @endunless>
                    <div class="form-text">JPG/PNG/WebP, maks 5 MB per foto, maks 50 foto sekali unggah.</div>
                </div></div>

                @if ($album->exists)
                    <div class="card mb-3"><div class="card-header">Foto dalam album ({{ $album->photos->count() }})</div><div class="card-body">
                        @if ($album->photos->isEmpty())
                            <p class="text-muted mb-0">Belum ada foto.</p>
                        @else
                            <div class="photo-grid">
                                @foreach ($album->photos as $photo)
                                    <div class="photo-item">
                                        <img src="{{ thumb_url($photo->path) }}" alt="" loading="lazy">
                                        <div class="p-2 d-flex gap-1">
                                            <input type="text" name="captions[{{ $photo->id }}]" value="{{ $photo->caption }}" class="form-control form-control-sm" placeholder="Keterangan" aria-label="Keterangan foto" maxlength="255">
                                            <button type="submit" form="del-photo-{{ $photo->id }}" class="btn btn-sm btn-outline-danger" title="Hapus foto"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div></div>
                @endif

                <div class="form-actions">
                    <a href="{{ route('admin.albums.index') }}" class="btn btn-light">Batal</a>
                    <button type="submit" name="action" value="draft" class="btn btn-outline-primary"><i class="bi bi-save me-1"></i>Simpan Draft</button>
                    <button type="submit" name="action" value="submit" class="btn btn-primary">
                        @if ($canPublish)<i class="bi bi-send-check me-1"></i>Simpan & Tayangkan @else<i class="bi bi-send me-1"></i>Kirim untuk Persetujuan @endif
                    </button>
                </div>
            </form>

            @if ($album->exists)
                @foreach ($album->photos as $photo)
                    <form method="POST" action="{{ route('admin.albums.photos.destroy', [$album, $photo]) }}" id="del-photo-{{ $photo->id }}" data-confirm="Hapus foto ini?" class="d-none">
                        @csrf @method('DELETE')
                    </form>
                @endforeach
            @endif
        </div>
        @if ($album->exists && $album->status === 'pending' && $super)
            <div class="col-lg-4">
                @include('admin.approval._actions', ['approve' => route('admin.approval.albums.approve', $album), 'reject' => route('admin.approval.albums.reject', $album), 'id' => 'a'.$album->id])
            </div>
        @endif
    </div>
@endsection
