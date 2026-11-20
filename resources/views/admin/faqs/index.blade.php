@extends('layouts.admin')
@section('title', 'FAQ')

@section('content')
    <x-admin.page-header title="FAQ (Pertanyaan Umum)">
        <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah FAQ</a>
    </x-admin.page-header>
    <x-admin.filter placeholder="Cari pertanyaan..." />
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th style="width:70px">Urutan</th><th>Pertanyaan</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @forelse ($faqs as $f)
                    <tr>
                        <td>{{ $f->urutan }}</td>
                        <td><div class="fw-semibold">{{ $f->pertanyaan }}</div><div class="small text-muted">{{ Str::limit(strip_tags($f->jawaban), 110) }}</div></td>
                        <td class="td-actions">
                            <a href="{{ route('admin.faqs.edit', $f) }}" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="bi bi-pencil"></i></a>
                            <x-admin.delete :action="route('admin.faqs.destroy', $f)" />
                        </td>
                    </tr>
                @empty
                    <x-admin.empty :colspan="3" />
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $faqs->links() }}</div>
@endsection
