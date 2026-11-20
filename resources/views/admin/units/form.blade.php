@extends('layouts.admin')
@php $super = auth()->user()->isSuperAdmin(); @endphp
@section('title', $unit->exists ? 'Ubah Unit' : 'Tambah Unit')

@section('content')
    <x-admin.page-header :title="$unit->exists ? 'Ubah: '.$unit->name : 'Tambah Unit'" :back="route('admin.units.index')" />

    <form method="POST" enctype="multipart/form-data"
          action="{{ $unit->exists ? route('admin.units.update', $unit) : route('admin.units.store') }}">
        @csrf
        @if ($unit->exists) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        @if ($super)
                            <div class="row">
                                <x-admin.input name="name" label="Nama unit" :value="$unit->name" required col="col-md-8 mb-3" />
                                <x-admin.select name="type" label="Jenis" :options="App\Models\Unit::TYPES" :value="$unit->type" required col="col-md-4 mb-3" />
                            </div>
                        @else
                            <div class="mb-3">
                                <label class="form-label">Nama unit</label>
                                <input type="text" class="form-control" value="{{ $unit->name }} ({{ $unit->typeLabel() }})" disabled>
                            </div>
                        @endif
                        <x-admin.textarea name="deskripsi" label="Deskripsi singkat" :value="$unit->deskripsi" rows="3" />
                        <x-admin.editor name="tugas_pokok" label="Tugas pokok" :value="$unit->tugas_pokok" />
                        <x-admin.editor name="layanan_unit" label="Layanan unit" :value="$unit->layanan_unit" height="180" />
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header">Kontak & Lokasi</div>
                    <div class="card-body">
                        <x-admin.textarea name="alamat" label="Alamat" :value="$unit->alamat" rows="2" />
                        <x-admin.input name="telepon" label="Telepon" :value="$unit->telepon" />
                        <x-admin.input type="email" name="email" label="Email" :value="$unit->email" />
                        <x-admin.textarea name="wilayah_pelayanan" label="Wilayah pelayanan" :value="$unit->wilayah_pelayanan" rows="2" help="Contoh: daftar kecamatan/desa (untuk Polsek)." />
                        <x-admin.input type="url" name="maps_url" label="Tautan Google Maps" :value="$unit->maps_url" help="Tempel tautan dari tombol Bagikan di Google Maps." />
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <x-admin.image name="foto" label="Foto unit / kantor" :current="$unit->foto" />
                        @if ($super)
                            <x-admin.input type="number" name="urutan" label="Urutan tampil" :value="$unit->urutan ?? 0" min="0" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <x-admin.form-actions :cancel="route('admin.units.index')" />
    </form>
@endsection
