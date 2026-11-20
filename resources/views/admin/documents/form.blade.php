@extends('layouts.admin')
@section('title', $document->exists ? 'Ubah Dokumen' : 'Tambah Dokumen')

@section('content')
    <x-admin.page-header :title="$document->exists ? 'Ubah Dokumen' : 'Tambah Dokumen'" :back="route('admin.documents.index')" />
    <div class="card" style="max-width:760px"><div class="card-body">
        <form method="POST" enctype="multipart/form-data" action="{{ $document->exists ? route('admin.documents.update', $document) : route('admin.documents.store') }}">
            @csrf
            @if ($document->exists) @method('PUT') @endif
            <x-admin.input name="judul" label="Judul dokumen" :value="$document->judul" required />
            <div class="row">
                <x-admin.select name="kategori" label="Kategori" :options="App\Models\Document::CATEGORIES" :value="$document->kategori" required col="col-md-8 mb-3" />
                <x-admin.input type="number" name="tahun" label="Tahun" :value="$document->tahun" min="1990" :max="now()->year + 1" col="col-md-4 mb-3" />
            </div>
            <x-admin.textarea name="deskripsi" label="Deskripsi" :value="$document->deskripsi" rows="3" />
            <x-admin.file name="file" label="Berkas" :current="$document->path" :required="! $document->exists" :removable="false" />
            <x-admin.form-actions :cancel="route('admin.documents.index')" />
        </form>
    </div></div>
@endsection
