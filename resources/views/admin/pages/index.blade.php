@extends('layouts.admin')
@section('title', 'Halaman Statis')

@section('content')
    <x-admin.page-header title="Halaman Statis" subtitle="Isi halaman Profil Polres dan sambutan Kapolres." />
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Halaman</th><th class="d-none d-md-table-cell">Kunci</th><th class="d-none d-md-table-cell">Terakhir diubah</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @forelse ($pages as $page)
                    <tr>
                        <td class="fw-semibold">{{ $page->judul }}
                            @if (! $page->isi)<div class="small text-warning">Belum diisi</div>@endif
                        </td>
                        <td class="d-none d-md-table-cell"><code>{{ $page->key }}</code></td>
                        <td class="d-none d-md-table-cell">{{ tgl($page->updated_at, 'D MMM YYYY HH:mm') }}</td>
                        <td class="td-actions">
                            <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i> Ubah</a>
                        </td>
                    </tr>
                @empty
                    <x-admin.empty :colspan="4" />
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
