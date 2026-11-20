@extends('layouts.admin')
@section('title', 'Menunggu Approval')

@section('content')
    <x-admin.page-header title="Menunggu Approval" subtitle="Konten dari Admin Unit yang perlu ditinjau sebelum tayang." />

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center">Konten <span class="badge bg-secondary ms-2">{{ $posts->count() }}</span></div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead><tr><th>Judul</th><th class="d-none d-md-table-cell">Jenis</th><th class="d-none d-md-table-cell">Unit / Penulis</th><th class="d-none d-lg-table-cell">Dikirim</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @forelse ($posts as $p)
                    <tr>
                        <td><a href="{{ route('admin.posts.show', [$p->type, $p]) }}" class="fw-semibold text-decoration-none">{{ Str::limit($p->judul, 80) }}</a>
                            <div class="small text-muted d-md-none">{{ $p->label() }} · {{ $p->unit?->name }}</div></td>
                        <td class="d-none d-md-table-cell">{{ $p->label() }}</td>
                        <td class="d-none d-md-table-cell small">{{ $p->unit?->name ?? '-' }}<br><span class="text-muted">{{ $p->author?->name }}</span></td>
                        <td class="d-none d-lg-table-cell small">{{ tgl($p->updated_at, 'D MMM YYYY HH:mm') }}</td>
                        <td class="td-actions">
                            <a href="{{ route('admin.posts.show', [$p->type, $p]) }}" class="btn btn-sm btn-light"><i class="bi bi-eye"></i> Tinjau</a>
                            <form method="POST" action="{{ route('admin.approval.posts.approve', $p) }}" class="d-inline" data-confirm="Setujui dan tayangkan konten ini?">
                                @csrf
                                <button class="btn btn-sm btn-success" title="Setujui"><i class="bi bi-check2"></i></button>
                            </form>
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal"
                                    data-action="{{ route('admin.approval.posts.reject', $p) }}" data-title="{{ $p->judul }}" title="Kembalikan"><i class="bi bi-arrow-return-left"></i></button>
                        </td>
                    </tr>
                @empty
                    <x-admin.empty :colspan="5" message="Tidak ada konten yang menunggu persetujuan." />
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Album Foto <span class="badge bg-secondary ms-2">{{ $albums->count() }}</span></div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead><tr><th>Album</th><th class="d-none d-md-table-cell">Unit / Penulis</th><th class="d-none d-md-table-cell">Foto</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @forelse ($albums as $a)
                    <tr>
                        <td><a href="{{ route('admin.albums.edit', $a) }}" class="fw-semibold text-decoration-none">{{ $a->judul }}</a></td>
                        <td class="d-none d-md-table-cell small">{{ $a->unit?->name ?? '-' }}<br><span class="text-muted">{{ $a->author?->name }}</span></td>
                        <td class="d-none d-md-table-cell">{{ $a->photos_count }}</td>
                        <td class="td-actions">
                            <a href="{{ route('admin.albums.edit', $a) }}" class="btn btn-sm btn-light"><i class="bi bi-eye"></i> Tinjau</a>
                            <form method="POST" action="{{ route('admin.approval.albums.approve', $a) }}" class="d-inline" data-confirm="Setujui dan tayangkan album ini?">
                                @csrf
                                <button class="btn btn-sm btn-success" title="Setujui"><i class="bi bi-check2"></i></button>
                            </form>
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal"
                                    data-action="{{ route('admin.approval.albums.reject', $a) }}" data-title="{{ $a->judul }}" title="Kembalikan"><i class="bi bi-arrow-return-left"></i></button>
                        </td>
                    </tr>
                @empty
                    <x-admin.empty :colspan="4" message="Tidak ada album yang menunggu persetujuan." />
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">Agenda <span class="badge bg-secondary ms-2">{{ $agendas->count() }}</span></div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead><tr><th>Agenda</th><th class="d-none d-md-table-cell">Waktu</th><th class="d-none d-md-table-cell">Unit / Penulis</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @forelse ($agendas as $ag)
                    <tr>
                        <td><a href="{{ route('admin.agendas.edit', $ag) }}" class="fw-semibold text-decoration-none">{{ $ag->judul }}</a>
                            <div class="small text-muted">{{ $ag->lokasi }}</div></td>
                        <td class="d-none d-md-table-cell small">{{ $ag->waktu() }}</td>
                        <td class="d-none d-md-table-cell small">{{ $ag->unit?->name ?? '-' }}<br><span class="text-muted">{{ $ag->author?->name }}</span></td>
                        <td class="td-actions">
                            <form method="POST" action="{{ route('admin.approval.agendas.approve', $ag) }}" class="d-inline" data-confirm="Setujui dan tayangkan agenda ini?">
                                @csrf
                                <button class="btn btn-sm btn-success" title="Setujui"><i class="bi bi-check2"></i></button>
                            </form>
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal"
                                    data-action="{{ route('admin.approval.agendas.reject', $ag) }}" data-title="{{ $ag->judul }}" title="Kembalikan"><i class="bi bi-arrow-return-left"></i></button>
                        </td>
                    </tr>
                @empty
                    <x-admin.empty :colspan="4" message="Tidak ada agenda yang menunggu persetujuan." />
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content" id="rejectForm">
                @csrf
                <div class="modal-header">
                    <h2 class="modal-title fs-6" id="rejectTitle">Kembalikan untuk revisi</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-2" id="rejectSubject"></p>
                    <label for="catatan_revisi" class="form-label">Catatan revisi</label>
                    <textarea name="catatan_revisi" id="catatan_revisi" rows="4" class="form-control" required maxlength="2000"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-danger">Kembalikan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('rejectModal').addEventListener('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        document.getElementById('rejectForm').action = btn.dataset.action;
        document.getElementById('rejectSubject').textContent = btn.dataset.title;
    });
</script>
@endpush
