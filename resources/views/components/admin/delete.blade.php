@props(['action', 'message' => 'Yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.', 'label' => null])
<form method="POST" action="{{ $action }}" class="d-inline" data-confirm="{{ $message }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus" aria-label="Hapus">
        <i class="bi bi-trash"></i>@if ($label) {{ $label }}@endif
    </button>
</form>
