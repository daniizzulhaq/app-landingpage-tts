@props(['cancel', 'label' => 'Simpan'])
<div class="form-actions">
    <a href="{{ $cancel }}" class="btn btn-light">Batal</a>
    @if ($slot->isEmpty())
        <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>{{ $label }}</button>
    @else
        {{ $slot }}
    @endif
</div>
