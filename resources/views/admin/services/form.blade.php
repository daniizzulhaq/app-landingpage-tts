@extends('layouts.admin')
@section('title', $service->exists ? 'Ubah Layanan' : 'Tambah Layanan')

@section('content')
    <x-admin.page-header :title="$service->exists ? 'Ubah Layanan: '.$service->nama : 'Tambah Layanan'" :back="route('admin.services.index')" />
    <form method="POST" enctype="multipart/form-data" action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}">
        @csrf
        @if ($service->exists) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card mb-3"><div class="card-body">
                    <x-admin.input name="nama" label="Nama layanan" :value="$service->nama" required />
                    <x-admin.textarea name="deskripsi" label="Deskripsi singkat" :value="$service->deskripsi" rows="2" />
                    <x-admin.textarea name="persyaratan" label="Persyaratan" :value="$service->persyaratan" rows="6"
                                      help="Tulis satu persyaratan per baris. Akan ditampilkan sebagai daftar." />
                    <x-admin.textarea name="prosedur" label="Prosedur / alur" :value="$service->prosedur" rows="6"
                                      help="Tulis satu langkah per baris. Akan ditampilkan sebagai langkah bernomor." />
                </div></div>
                <div class="card"><div class="card-header">Formulir yang dapat diunduh</div><div class="card-body">
                    @if ($service->exists && $service->files->isNotEmpty())
                        <ul class="list-group mb-3">
                            @foreach ($service->files as $f)
                                <li class="list-group-item d-flex align-items-center gap-2">
                                    <i class="bi bi-file-earmark-arrow-down"></i>
                                    <a href="{{ media_url($f->path) }}" target="_blank" class="flex-grow-1 text-truncate">{{ $f->nama }}</a>
                                    <span class="form-check mb-0 small">
                                        <input class="form-check-input" type="checkbox" name="hapus_files[]" value="{{ $f->id }}" id="hf{{ $f->id }}">
                                        <label class="form-check-label text-danger" for="hf{{ $f->id }}">Hapus</label>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <div id="file-rows">
                        <div class="row g-2 mb-2 file-row">
                            <div class="col-md-5"><input type="text" name="file_names[]" class="form-control" placeholder="Nama formulir (opsional)" aria-label="Nama formulir"></div>
                            <div class="col-md-7"><input type="file" name="files[]" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.webp" aria-label="Berkas formulir"></div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-light" id="add-file-row"><i class="bi bi-plus"></i> Tambah baris formulir</button>
                    <div class="form-text">PDF/JPG/PNG/WebP, maks 10 MB per berkas.</div>
                </div></div>
            </div>
            <div class="col-lg-4">
                <div class="card"><div class="card-body">
                    <x-admin.select name="ikon" label="Ikon" :options="App\Models\Service::ICONS" :value="$service->ikon" required />
                    <x-admin.select name="unit_id" label="Unit pelaksana" :options="$units" :value="$service->unit_id" placeholder="— Pilih unit —" />
                    <x-admin.textarea name="biaya" label="Biaya" :value="$service->biaya" rows="2" help="Tulis sesuai ketentuan resmi yang berlaku." />
                    <x-admin.textarea name="jadwal" label="Jadwal pelayanan" :value="$service->jadwal" rows="2" />
                    <x-admin.textarea name="lokasi" label="Lokasi pelayanan" :value="$service->lokasi" rows="2" />
                    <x-admin.input type="number" name="urutan" label="Urutan" :value="$service->urutan ?? 0" min="0" />
                </div></div>
            </div>
        </div>
        <x-admin.form-actions :cancel="route('admin.services.index')" />
    </form>
@endsection

@push('scripts')
<script>
    document.getElementById('add-file-row').addEventListener('click', function () {
        const rows = document.getElementById('file-rows');
        const clone = rows.querySelector('.file-row').cloneNode(true);
        clone.querySelectorAll('input').forEach(i => i.value = '');
        rows.appendChild(clone);
    });
</script>
@endpush
