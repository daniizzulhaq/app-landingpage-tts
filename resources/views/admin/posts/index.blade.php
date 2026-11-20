@extends('layouts.admin')
@php $label = App\Models\Post::typeLabel($type); @endphp
@section('title', $label)

@section('content')
    <x-admin.page-header :title="App\Models\Post::typeLabel($type, true)">
        @can('createType', [App\Models\Post::class, $type])
            <a href="{{ route('admin.posts.create', $type) }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah {{ $label }}</a>
        @endcan
    </x-admin.page-header>

    <x-admin.filter placeholder="Cari judul...">
        <div class="col-6 col-md-2">
            <select name="status" class="form-select" aria-label="Status">
                <option value="">Semua status</option>
                @foreach (App\Models\Post::$statusLabels as $k => $v)
                    <option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="category_id" class="form-select" aria-label="Kategori">
                <option value="">Semua kategori</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->id }}" @selected(request('category_id') == $c->id)>{{ $c->name }}</option>
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
                <thead>
                <tr>
                    <th style="width:80px">Gambar</th><th>Judul</th>
                    <th class="d-none d-lg-table-cell">Unit</th>
                    <th>Status</th>
                    <th class="d-none d-md-table-cell">Tanggal</th>
                    <th class="d-none d-lg-table-cell text-end">Dilihat</th>
                    <th class="text-end">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($posts as $p)
                    <tr>
                        <td><img src="{{ thumb_url($p->thumbnail) }}" class="table-thumb" alt="" loading="lazy"></td>
                        <td>
                            <div class="fw-semibold">{{ Str::limit($p->judul, 80) }}</div>
                            <div class="small text-muted">
                                {{ $p->category?->name ?? 'Tanpa kategori' }} · {{ $p->author?->name ?? '-' }}
                                @if ($p->is_penting)<span class="text-danger ms-1"><i class="bi bi-exclamation-circle"></i> Penting</span>@endif
                            </div>
                            @if ($p->status === 'rejected' && $p->catatan_revisi)
                                <div class="small text-danger mt-1"><i class="bi bi-chat-left-text"></i> {{ Str::limit($p->catatan_revisi, 120) }}</div>
                            @endif
                        </td>
                        <td class="d-none d-lg-table-cell small">{{ $p->unit?->name ?? '-' }}</td>
                        <td><x-admin.status :model="$p" /></td>
                        <td class="d-none d-md-table-cell small">{{ tgl($p->displayDate(), 'D MMM YYYY') }}</td>
                        <td class="d-none d-lg-table-cell text-end small">{{ number_format($p->views) }}</td>
                        <td class="td-actions">
                            @if ($p->isPublished() && Route::has('site.posts.show'))
                                <a href="{{ $p->url() }}" target="_blank" class="btn btn-sm btn-light" title="Lihat di situs"><i class="bi bi-box-arrow-up-right"></i></a>
                            @else
                                <a href="{{ route('admin.posts.show', [$type, $p]) }}" class="btn btn-sm btn-light" title="Pratinjau"><i class="bi bi-eye"></i></a>
                            @endif
                            @can('update', $p)
                                <a href="{{ route('admin.posts.edit', [$type, $p]) }}" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="bi bi-pencil"></i></a>
                            @endcan
                            @can('delete', $p)
                                <x-admin.delete :action="route('admin.posts.destroy', [$type, $p])" />
                            @endcan
                        </td>
                    </tr>
                @empty
                    <x-admin.empty :colspan="7" />
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $posts->links() }}</div>
@endsection
