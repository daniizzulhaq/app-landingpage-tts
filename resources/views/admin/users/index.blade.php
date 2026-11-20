@extends('layouts.admin')
@section('title', 'Pengguna')

@section('content')
    <x-admin.page-header title="Pengguna">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Pengguna</a>
    </x-admin.page-header>

    <x-admin.filter placeholder="Cari nama atau email...">
        <div class="col-6 col-md-2">
            <select name="role" class="form-select" aria-label="Role">
                <option value="">Semua role</option>
                @foreach (App\Models\User::ROLES as $k => $v)
                    <option value="{{ $k }}" @selected(request('role') === $k)>{{ $v }}</option>
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
                <thead><tr><th>Nama</th><th class="d-none d-md-table-cell">Email</th><th>Role</th><th class="d-none d-md-table-cell">Unit</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @forelse ($users as $u)
                    <tr>
                        <td class="fw-semibold">{{ $u->name }}<div class="small text-muted d-md-none">{{ $u->email }}</div></td>
                        <td class="d-none d-md-table-cell">{{ $u->email }}</td>
                        <td>{{ $u->roleLabel() }}</td>
                        <td class="d-none d-md-table-cell">{{ $u->unit?->name ?? '-' }}</td>
                        <td>
                            <span class="status-badge {{ $u->is_active ? 'status-published' : 'status-draft' }}">{{ $u->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </td>
                        <td class="td-actions">
                            <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="bi bi-pencil"></i></a>
                            @unless ($u->is(auth()->user()))
                                <x-admin.delete :action="route('admin.users.destroy', $u)" />
                            @endunless
                        </td>
                    </tr>
                @empty
                    <x-admin.empty :colspan="6" />
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $users->links() }}</div>
@endsection
