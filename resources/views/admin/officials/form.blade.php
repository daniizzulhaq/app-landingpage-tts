@extends('layouts.admin')
@section('title', $official->exists ? 'Ubah Pejabat' : 'Tambah Pejabat')

@section('content')
    <x-admin.page-header :title="$official->exists ? 'Ubah Pejabat' : 'Tambah Pejabat'" :back="route('admin.officials.index')" />

    <form method="POST" enctype="multipart/form-data"
          action="{{ $official->exists ? route('admin.officials.update', $official) : route('admin.officials.store') }}">
        @csrf
        @if ($official->exists) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card"><div class="card-body">
                    <div class="row">
                        <x-admin.input name="nama" label="Nama" :value="$official->nama" required col="col-md-7 mb-3" />
                        <x-admin.input name="pangkat" label="Pangkat / NRP" :value="$official->pangkat" col="col-md-5 mb-3" />
                    </div>
                    <x-admin.input name="jabatan" label="Jabatan" :value="$official->jabatan" required />
                    <x-admin.editor name="tugas" label="Profil / tugas / sambutan singkat" :value="$official->tugas" height="200" />
                </div></div>
            </div>
            <div class="col-lg-4">
                <div class="card"><div class="card-body">
                    <x-admin.select name="level" label="Level" :options="App\Models\Official::LEVELS" :value="$official->level" required
                                    help="Kapolres & Wakapolres tampil di halaman Profil Pimpinan dan puncak bagan." />
                    <x-admin.select name="unit_id" label="Unit" :options="$units->pluck('name', 'id')->all()" :value="$official->unit_id" placeholder="— Tanpa unit —" />
                    <x-admin.image name="foto" label="Foto" :current="$official->foto" />
                    <x-admin.input type="number" name="urutan" label="Urutan" :value="$official->urutan ?? 0" min="0" />
                </div></div>
            </div>
        </div>
        <x-admin.form-actions :cancel="route('admin.officials.index')" />
    </form>
@endsection
