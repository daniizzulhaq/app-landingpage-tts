@extends('layouts.admin')
@section('title', 'Kategori')

@section('content')
    <x-admin.page-header title="Kategori Konten">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Kategori</a>
    </x-admin.page-header>
    <x-admin.filter placeholder="Cari kategori..." />
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Nama</th><th class="d-none d-md-table-cell">Slug</th><th>Jumlah konten</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @forelse ($categories as $c)
                    <tr>
                        <td class="fw-semibold">{{ $c->name }}</td>
                        <td class="d-none d-md-table-cell"><code>{{ $c->slug }}</code></td>
                        <td>{{ $c->posts_count }}</td>
                        <td class="td-actions">
                            <a href="{{ route('admin.categories.edit', $c) }}" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="bi bi-pencil"></i></a>
                            <x-admin.delete :action="route('admin.categories.destroy', $c)" />
                        </td>
                    </tr>
                @empty
                    <x-admin.empty :colspan="4" />
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $categories->links() }}</div>
@endsection
