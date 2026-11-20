@extends('layouts.admin')
@section('title', 'Album Foto')

@section('content')
    <x-admin.page-header title="Album Foto">
        @can('create', App\Models\Album::class)
            <a href="{{ route('admin.albums.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Album</a>
        @endcan
    </x-admin.page-header>

    <x-admin.filter placeholder="Cari judul album...">
        <div class="col-6 col-md-2">
            <select name="status" class="form-select" aria-label="Status">
                <option value="">Semua status</option>
                @foreach (App\Models\Album::$statusLabels as $k => $v)
                    <option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        @if ($units->isNotEmpty())
            <div class="col-6 col-md-2">
                <select name="unit_id" class="form-select" aria-label="Unit">
                    <option value="">Semua unit</option>
                    @foreach ($units as $u)
                        <option value="{{ $u->id }}" @selected(request('unit_id') == $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </x-admin.filter>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th style="width:80px">Sampul</th><th>Judul</th><th class="d-none d-md-table-cell">Unit</th><th class="d-none d-md-table-cell">Foto</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @forelse ($albums as $a)
                    <tr>
                        <td><img src="{{ thumb_url($a->cover?->path) }}" class="table-thumb" alt="" loading="lazy"></td>
                        <td><div class="fw-semibold">{{ $a->judul }}</div><div class="small text-muted">{{ tgl($a->tanggal) }}</div>
                            @if ($a->status === 'rejected' && $a->catatan_revisi)<div class="small text-danger"><i class="bi bi-chat-left-text"></i> {{ Str::limit($a->catatan_revisi, 100) }}</div>@endif
                        </td>
                        <td class="d-none d-md-table-cell small">{{ $a->unit?->name ?? '-' }}</td>
                        <td class="d-none d-md-table-cell">{{ $a->photos_count }}</td>
                        <td><x-admin.status :model="$a" /></td>
                        <td class="td-actions">
                            @if ($a->isPublished() && Route::has('site.gallery.album'))
                                <a href="{{ route('site.gallery.album', $a->slug) }}" target="_blank" class="btn btn-sm btn-light" title="Lihat"><i class="bi bi-box-arrow-up-right"></i></a>
                            @endif
                            @can('update', $a)
                                <a href="{{ route('admin.albums.edit', $a) }}" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="bi bi-pencil"></i></a>
                            @endcan
                            @can('delete', $a)
                                <x-admin.delete :action="route('admin.albums.destroy', $a)" message="Hapus album beserta semua fotonya?" />
                            @endcan
                        </td>
                    </tr>
                @empty
                    <x-admin.empty :colspan="6" />
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $albums->links() }}</div>
@endsection
