@extends('layouts.admin')
@section('title', 'Permohonan Informasi')

@section('content')
    <x-admin.page-header title="Permohonan Informasi Publik" />
    <x-admin.filter placeholder="Cari nama, kontak, atau isi permohonan...">
        <div class="col-12 col-md-3">
            <select name="status" class="form-select" aria-label="Status">
                <option value="">Semua status</option>
                @foreach (App\Models\InformationRequest::STATUSES as $k => $v)
                    <option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
    </x-admin.filter>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Pemohon</th><th class="d-none d-md-table-cell">Informasi diminta</th><th class="d-none d-md-table-cell">Tanggal</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @forelse ($items as $r)
                    <tr class="{{ $r->status === 'baru' ? 'fw-semibold' : '' }}">
                        <td>{{ $r->nama }}<div class="small text-muted fw-normal">{{ $r->kontak }}</div></td>
                        <td class="d-none d-md-table-cell small fw-normal">{{ Str::limit($r->informasi_diminta, 90) }}</td>
                        <td class="d-none d-md-table-cell small fw-normal">{{ tgl($r->created_at, 'D MMM YYYY HH:mm') }}</td>
                        <td><span class="status-badge status-{{ $r->status }}">{{ $r->statusLabel() }}</span></td>
                        <td class="td-actions">
                            <a href="{{ route('admin.information-requests.show', $r) }}" class="btn btn-sm btn-outline-primary" title="Lihat"><i class="bi bi-eye"></i></a>
                            <x-admin.delete :action="route('admin.information-requests.destroy', $r)" />
                        </td>
                    </tr>
                @empty
                    <x-admin.empty :colspan="5" />
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $items->links() }}</div>
@endsection
