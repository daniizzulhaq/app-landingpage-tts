@extends('layouts.admin')
@section('title', 'Dashboard')
@php $super = auth()->user()->isSuperAdmin(); @endphp

@section('content')
    <x-admin.page-header title="Dashboard"
        :subtitle="'Selamat datang, '.auth()->user()->name.(auth()->user()->unit ? ' · '.auth()->user()->unit->name : '')" />

    <div class="row g-3 mb-3">
        @foreach ($stats as $s)
            <div class="col-6 col-md-4 col-xl-2">
                <a href="{{ $s['url'] }}" class="card stat-card h-100 text-decoration-none text-reset">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="text-muted small">{{ $s['label'] }}</div>
                            <div class="stat-icon"><i class="bi {{ $s['icon'] }}"></i></div>
                        </div>
                        <div class="stat-value">{{ number_format($s['total']) }}</div>
                        <div class="small text-muted">{{ number_format($s['tayang']) }} tayang</div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center">
                    <i class="bi bi-hourglass-split me-2 text-warning"></i>Menunggu Persetujuan
                    <span class="badge bg-warning text-dark ms-2">{{ $pendingCount }}</span>
                    @if ($super && Route::has('admin.approval.index'))
                        <a href="{{ route('admin.approval.index') }}" class="ms-auto small">Lihat semua</a>
                    @endif
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($pendingPosts as $p)
                        <li class="list-group-item">
                            <a href="{{ route('admin.posts.show', [$p->type, $p]) }}" class="text-decoration-none fw-semibold">{{ Str::limit($p->judul, 70) }}</a>
                            <div class="small text-muted">{{ $p->label() }} · {{ $p->unit?->name ?? 'Polres' }} · {{ $p->updated_at->diffForHumans() }}</div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted small py-4 text-center">Tidak ada konten yang menunggu persetujuan.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        @if ($super)
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center">
                        <i class="bi bi-envelope-paper me-2 text-primary"></i>Permohonan Informasi
                        <span class="badge bg-primary ms-2">{{ $newRequests }} baru</span>
                        <a href="{{ route('admin.information-requests.index') }}" class="ms-auto small">Lihat semua</a>
                    </div>
                    <ul class="list-group list-group-flush">
                        @forelse ($latestRequests as $r)
                            <li class="list-group-item d-flex align-items-start gap-2">
                                <div class="flex-grow-1">
                                    <a href="{{ route('admin.information-requests.show', $r) }}" class="text-decoration-none fw-semibold">{{ $r->nama }}</a>
                                    <div class="small text-muted">{{ Str::limit($r->informasi_diminta, 80) }}</div>
                                </div>
                                <span class="status-badge status-{{ $r->status }}">{{ $r->statusLabel() }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted small py-4 text-center">Belum ada permohonan.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        @else
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header"><i class="bi bi-arrow-return-left me-2 text-danger"></i>Dikembalikan untuk Revisi</div>
                    <ul class="list-group list-group-flush">
                        @forelse ($rejected as $p)
                            <li class="list-group-item">
                                <a href="{{ route('admin.posts.edit', [$p->type, $p]) }}" class="text-decoration-none fw-semibold">{{ Str::limit($p->judul, 70) }}</a>
                                <div class="small text-danger">{{ Str::limit($p->catatan_revisi, 100) }}</div>
                            </li>
                        @empty
                            <li class="list-group-item text-muted small py-4 text-center">Tidak ada konten yang perlu direvisi.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        @endif

        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="bi bi-graph-up-arrow me-2"></i>Konten Terpopuler</div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Judul</th><th class="d-none d-md-table-cell">Jenis</th><th class="text-end">Dilihat</th></tr></thead>
                        <tbody>
                        @forelse ($popular as $p)
                            <tr>
                                <td>
                                    @if (Route::has('site.posts.show'))
                                        <a href="{{ $p->url() }}" target="_blank" class="text-decoration-none">{{ Str::limit($p->judul, 90) }}</a>
                                    @else
                                        {{ Str::limit($p->judul, 90) }}
                                    @endif
                                </td>
                                <td class="d-none d-md-table-cell">{{ $p->label() }}</td>
                                <td class="text-end fw-semibold">{{ number_format($p->views) }}</td>
                            </tr>
                        @empty
                            <x-admin.empty :colspan="3" />
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
