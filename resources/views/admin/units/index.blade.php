@extends('layouts.admin')
@section('title', auth()->user()->isSuperAdmin() ? 'Unit' : 'Profil Unit')

@section('content')
    <x-admin.page-header :title="auth()->user()->isSuperAdmin() ? 'Unit / Satuan Kerja' : 'Profil Unit'">
        @can('create', App\Models\Unit::class)
            <a href="{{ route('admin.units.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Unit</a>
        @endcan
    </x-admin.page-header>

    @if (auth()->user()->isSuperAdmin())
        <x-admin.filter placeholder="Cari nama unit...">
            <div class="col-12 col-md-3">
                <select name="type" class="form-select" aria-label="Jenis">
                    <option value="">Semua jenis</option>
                    @foreach (App\Models\Unit::TYPES as $k => $v)
                        <option value="{{ $k }}" @selected(request('type') === $k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
        </x-admin.filter>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                <tr>
                    <th style="width:72px">Foto</th>
                    <th>Nama Unit</th>
                    <th>Jenis</th>
                    <th class="d-none d-md-table-cell">Telepon</th>
                    <th class="d-none d-md-table-cell">Urutan</th>
                    <th class="text-end">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($units as $unit)
                    <tr>
                        <td><img src="{{ thumb_url($unit->foto) }}" alt="" class="table-thumb" loading="lazy"></td>
                        <td class="fw-semibold">{{ $unit->name }}</td>
                        <td>{{ $unit->typeLabel() }}</td>
                        <td class="d-none d-md-table-cell">{{ $unit->telepon ?: '-' }}</td>
                        <td class="d-none d-md-table-cell">{{ $unit->urutan }}</td>
                        <td class="td-actions">
                            @if (Route::has('site.units.show'))
                                <a href="{{ route('site.units.show', $unit->slug) }}" target="_blank" class="btn btn-sm btn-light" title="Lihat"><i class="bi bi-eye"></i></a>
                            @endif
                            @can('update', $unit)
                                <a href="{{ route('admin.units.edit', $unit) }}" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="bi bi-pencil"></i></a>
                            @endcan
                            @can('delete', $unit)
                                <x-admin.delete :action="route('admin.units.destroy', $unit)" message="Hapus unit ini? Konten yang terkait unit akan kehilangan unitnya." />
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
    <div class="mt-3">{{ $units->links() }}</div>
@endsection
