@extends('layouts.admin')
@section('title', 'Layanan')

@section('content')
    <x-admin.page-header title="Informasi Layanan">
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Layanan</a>
    </x-admin.page-header>
    <x-admin.filter placeholder="Cari nama layanan...">
        <div class="col-12 col-md-3">
            <select name="unit_id" class="form-select" aria-label="Unit">
                <option value="">Semua unit</option>
                @foreach ($units as $u)
                    <option value="{{ $u->id }}" @selected(request('unit_id') == $u->id)>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
    </x-admin.filter>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th style="width:50px"></th><th>Layanan</th><th class="d-none d-md-table-cell">Unit pelaksana</th><th class="d-none d-md-table-cell">Formulir</th><th class="d-none d-md-table-cell">Urutan</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @forelse ($services as $s)
                    <tr>
                        <td><i class="bi {{ $s->ikon }} fs-4 text-primary"></i></td>
                        <td><div class="fw-semibold">{{ $s->nama }}</div><div class="small text-muted">{{ Str::limit($s->deskripsi, 90) }}</div></td>
                        <td class="d-none d-md-table-cell small">{{ $s->unit?->name ?? '-' }}</td>
                        <td class="d-none d-md-table-cell">{{ $s->files_count }}</td>
                        <td class="d-none d-md-table-cell">{{ $s->urutan }}</td>
                        <td class="td-actions">
                            @if (Route::has('site.services.show'))
                                <a href="{{ route('site.services.show', $s->slug) }}" target="_blank" class="btn btn-sm btn-light" title="Lihat"><i class="bi bi-box-arrow-up-right"></i></a>
                            @endif
                            <a href="{{ route('admin.services.edit', $s) }}" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="bi bi-pencil"></i></a>
                            <x-admin.delete :action="route('admin.services.destroy', $s)" message="Hapus layanan beserta formulirnya?" />
                        </td>
                    </tr>
                @empty
                    <x-admin.empty :colspan="6" />
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $services->links() }}</div>
@endsection
