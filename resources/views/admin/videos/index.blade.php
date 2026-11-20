@extends('layouts.admin')
@section('title', 'Video')

@section('content')
    <x-admin.page-header title="Galeri Video">
        <a href="{{ route('admin.videos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Video</a>
    </x-admin.page-header>
    <x-admin.filter placeholder="Cari judul video..." />
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th style="width:96px">Pratinjau</th><th>Judul</th><th class="d-none d-md-table-cell">Tanggal</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @forelse ($videos as $v)
                    <tr>
                        <td><img src="{{ $v->thumbnailUrl() }}" class="table-thumb" style="width:88px;height:50px" alt="" loading="lazy"></td>
                        <td><div class="fw-semibold">{{ $v->judul }}</div><a href="{{ $v->youtube_url }}" target="_blank" rel="noopener" class="small text-break">{{ $v->youtube_url }}</a></td>
                        <td class="d-none d-md-table-cell small">{{ tgl($v->tanggal) }}</td>
                        <td class="td-actions">
                            <a href="{{ route('admin.videos.edit', $v) }}" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="bi bi-pencil"></i></a>
                            <x-admin.delete :action="route('admin.videos.destroy', $v)" />
                        </td>
                    </tr>
                @empty
                    <x-admin.empty :colspan="4" />
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $videos->links() }}</div>
@endsection
