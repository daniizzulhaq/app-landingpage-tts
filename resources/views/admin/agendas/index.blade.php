@extends('layouts.admin')
@section('title', 'Agenda')

@section('content')
    <x-admin.page-header title="Agenda">
        @can('create', App\Models\Agenda::class)
            <a href="{{ route('admin.agendas.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Agenda</a>
        @endcan
    </x-admin.page-header>
    <x-admin.filter placeholder="Cari agenda...">
        <div class="col-6 col-md-2">
            <select name="waktu" class="form-select" aria-label="Waktu">
                <option value="">Semua waktu</option>
                <option value="mendatang" @selected(request('waktu') === 'mendatang')>Mendatang</option>
                <option value="lewat" @selected(request('waktu') === 'lewat')>Sudah lewat</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="status" class="form-select" aria-label="Status">
                <option value="">Semua status</option>
                @foreach (App\Models\Agenda::$statusLabels as $k => $v)
                    <option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        @if ($units->isNotEmpty())
            <div class="col-12 col-md-2">
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
                <thead><tr><th>Agenda</th><th class="d-none d-md-table-cell">Waktu</th><th class="d-none d-lg-table-cell">Unit</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @forelse ($agendas as $a)
                    <tr>
                        <td><div class="fw-semibold">{{ $a->judul }}</div><div class="small text-muted">{{ $a->lokasi }}</div>
                            <div class="small text-muted d-md-none">{{ tgl($a->mulai, 'D MMM YYYY HH:mm') }}</div>
                            @if ($a->status === 'rejected' && $a->catatan_revisi)<div class="small text-danger"><i class="bi bi-chat-left-text"></i> {{ Str::limit($a->catatan_revisi, 100) }}</div>@endif
                        </td>
                        <td class="d-none d-md-table-cell small">{{ tgl($a->mulai, 'D MMM YYYY · HH:mm') }}</td>
                        <td class="d-none d-lg-table-cell small">{{ $a->unit?->name ?? '-' }}</td>
                        <td><x-admin.status :model="$a" /></td>
                        <td class="td-actions">
                            @can('update', $a)
                                <a href="{{ route('admin.agendas.edit', $a) }}" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="bi bi-pencil"></i></a>
                            @endcan
                            @can('delete', $a)
                                <x-admin.delete :action="route('admin.agendas.destroy', $a)" />
                            @endcan
                        </td>
                    </tr>
                @empty
                    <x-admin.empty :colspan="5" />
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $agendas->links() }}</div>
@endsection
