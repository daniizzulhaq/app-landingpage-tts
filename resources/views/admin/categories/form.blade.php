@extends('layouts.admin')
@section('title', $category->exists ? 'Ubah Kategori' : 'Tambah Kategori')

@section('content')
    <x-admin.page-header :title="$category->exists ? 'Ubah Kategori' : 'Tambah Kategori'" :back="route('admin.categories.index')" />
    <div class="card" style="max-width:560px"><div class="card-body">
        <form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
            @csrf
            @if ($category->exists) @method('PUT') @endif
            <x-admin.input name="name" label="Nama kategori" :value="$category->name" required />
            <x-admin.form-actions :cancel="route('admin.categories.index')" />
        </form>
    </div></div>
@endsection
