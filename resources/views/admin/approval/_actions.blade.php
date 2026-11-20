{{-- Tombol Setujui / Kembalikan + form catatan revisi --}}
<div class="card mt-3"><div class="card-body">
    <form method="POST" action="{{ $approve }}" class="mb-2" data-confirm="Setujui dan tayangkan konten ini?">
        @csrf
        <button class="btn btn-success w-100"><i class="bi bi-check2-circle me-1"></i>Setujui & Tayangkan</button>
    </form>
    <button class="btn btn-outline-danger w-100" type="button" data-bs-toggle="collapse" data-bs-target="#reject-{{ $id }}" aria-expanded="false">
        <i class="bi bi-arrow-return-left me-1"></i>Kembalikan
    </button>
    <div class="collapse mt-2" id="reject-{{ $id }}">
        <form method="POST" action="{{ $reject }}">
            @csrf
            <label for="note-{{ $id }}" class="form-label small">Catatan revisi untuk penulis</label>
            <textarea name="catatan_revisi" id="note-{{ $id }}" rows="3" class="form-control mb-2" required maxlength="2000"></textarea>
            <button class="btn btn-danger btn-sm w-100">Kirim Catatan & Kembalikan</button>
        </form>
    </div>
</div></div>
