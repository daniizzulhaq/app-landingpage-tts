@extends('layouts.admin')
@section('title', 'Kanal Pengaduan')

@section('content')
    <x-admin.page-header title="Kanal Pengaduan Resmi" subtitle="Ditampilkan di halaman Pengaduan dan Bantuan.">
        <a href="{{ route('admin.complaint-channels.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Kanal</a>
    </x-admin.page-header>
    <x-admin.filter placeholder="Cari nama kanal..." />
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th style="width:50px"></th><th>Nama</th><th class="d-none d-md-table-cell">Tautan</th><th class="d-none d-md-table-cell">Urutan</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @forelse ($channels as $c)
                    <tr>
                        <td><i class="bi {{ $c->ikon }} fs-4 text-primary"></i></td>
                        <td><div class="fw-semibold">{{ $c->nama }}</div><div class="small text-muted">{{ Str::limit($c->deskripsi, 90) }}</div></td>
                        <td class="d-none d-md-table-cell small text-break">{{ $c->url ?: '-' }}</td>
                        <td class="d-none d-md-table-cell">{{ $c->urutan }}</td>
                        <td class="td-actions">
                            <a href="{{ route('admin.complaint-channels.edit', $c) }}" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="bi bi-pencil"></i></a>
                            <x-admin.delete :action="route('admin.complaint-channels.destroy', $c)" />
                        </td>
                    </tr>
                @empty
                    <x-admin.empty :colspan="5" />
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $channels->links() }}</div>
@endsection
