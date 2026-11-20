@extends('layouts.admin')
@section('title', 'Slider')

@section('content')
    <x-admin.page-header title="Slider Beranda">
        <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Slider</a>
    </x-admin.page-header>

    <x-admin.filter placeholder="Cari judul slider...">
        <div class="col-12 col-md-3">
            <select name="status" class="form-select" aria-label="Status">
                <option value="">Semua status</option>
                <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                <option value="nonaktif" @selected(request('status') === 'nonaktif')>Nonaktif</option>
            </select>
        </div>
    </x-admin.filter>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th style="width:80px">Gambar</th><th>Judul</th><th class="d-none d-md-table-cell">Urutan</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @forelse ($sliders as $s)
                    <tr>
                        <td><img src="{{ thumb_url($s->gambar) }}" class="table-thumb" alt="" loading="lazy"></td>
                        <td><div class="fw-semibold">{{ $s->judul }}</div><div class="small text-muted text-truncate" style="max-width:360px">{{ $s->subjudul }}</div></td>
                        <td class="d-none d-md-table-cell">{{ $s->urutan }}</td>
                        <td><span class="status-badge {{ $s->is_active ? 'status-published' : 'status-draft' }}">{{ $s->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td class="td-actions">
                            <a href="{{ route('admin.sliders.edit', $s) }}" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="bi bi-pencil"></i></a>
                            <x-admin.delete :action="route('admin.sliders.destroy', $s)" />
                        </td>
                    </tr>
                @empty
                    <x-admin.empty :colspan="5" message="Belum ada slider. Beranda akan menampilkan banner default." />
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $sliders->links() }}</div>
@endsection
