@extends('layouts.admin')
@section('title', 'Dokumen PPID')

@section('content')
    <x-admin.page-header title="Dokumen PPID">
        <a href="{{ route('admin.documents.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Dokumen</a>
    </x-admin.page-header>
    <x-admin.filter placeholder="Cari judul dokumen...">
        <div class="col-6 col-md-3">
            <select name="kategori" class="form-select" aria-label="Kategori">
                <option value="">Semua kategori</option>
                @foreach (App\Models\Document::CATEGORIES as $k => $v)
                    <option value="{{ $k }}" @selected(request('kategori') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="tahun" class="form-select" aria-label="Tahun">
                <option value="">Semua tahun</option>
                @foreach ($years as $y)
                    <option value="{{ $y }}" @selected(request('tahun') == $y)>{{ $y }}</option>
                @endforeach
            </select>
        </div>
    </x-admin.filter>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Judul</th><th class="d-none d-md-table-cell">Kategori</th><th>Tahun</th><th class="d-none d-md-table-cell">Berkas</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @forelse ($documents as $d)
                    <tr>
                        <td><div class="fw-semibold">{{ $d->judul }}</div><div class="small text-muted d-md-none">{{ $d->kategoriLabel() }}</div></td>
                        <td class="d-none d-md-table-cell small">{{ $d->kategoriLabel() }}</td>
                        <td>{{ $d->tahun ?? '-' }}</td>
                        <td class="d-none d-md-table-cell"><a href="{{ media_url($d->path) }}" target="_blank" class="small"><i class="bi bi-download"></i> {{ $d->extension() }}</a></td>
                        <td class="td-actions">
                            <a href="{{ route('admin.documents.edit', $d) }}" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="bi bi-pencil"></i></a>
                            <x-admin.delete :action="route('admin.documents.destroy', $d)" />
                        </td>
                    </tr>
                @empty
                    <x-admin.empty :colspan="5" />
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $documents->links() }}</div>
@endsection
