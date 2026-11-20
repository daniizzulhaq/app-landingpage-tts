@extends('layouts.admin')
@section('title', 'Pejabat')

@section('content')
    <x-admin.page-header title="Pejabat">
        <a href="{{ route('admin.officials.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Pejabat</a>
    </x-admin.page-header>

    <x-admin.filter placeholder="Cari nama atau jabatan...">
        <div class="col-6 col-md-2">
            <select name="level" class="form-select" aria-label="Level">
                <option value="">Semua level</option>
                @foreach (App\Models\Official::LEVELS as $k => $v)
                    <option value="{{ $k }}" @selected(request('level') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-3">
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
                <thead>
                <tr><th style="width:56px">Foto</th><th>Nama</th><th>Jabatan</th><th class="d-none d-md-table-cell">Unit</th><th class="d-none d-md-table-cell">Level</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                @forelse ($officials as $o)
                    <tr>
                        <td><img src="{{ thumb_url($o->foto, 'img/avatar.svg') }}" alt="" class="table-avatar" loading="lazy"></td>
                        <td><div class="fw-semibold">{{ $o->nama }}</div><div class="small text-muted">{{ $o->pangkat }}</div></td>
                        <td>{{ $o->jabatan }}</td>
                        <td class="d-none d-md-table-cell">{{ $o->unit?->name ?? '-' }}</td>
                        <td class="d-none d-md-table-cell">{{ App\Models\Official::LEVELS[$o->level] ?? $o->level }}</td>
                        <td class="td-actions">
                            <a href="{{ route('admin.officials.edit', $o) }}" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="bi bi-pencil"></i></a>
                            <x-admin.delete :action="route('admin.officials.destroy', $o)" />
                        </td>
                    </tr>
                @empty
                    <x-admin.empty :colspan="6" />
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $officials->links() }}</div>
@endsection
