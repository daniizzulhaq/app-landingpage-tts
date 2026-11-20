@props(['name', 'label', 'current' => null, 'help' => 'Format JPG, PNG, WebP, atau PDF. Maks 10 MB.', 'accept' => '.jpg,.jpeg,.png,.webp,.pdf', 'required' => false, 'removable' => true])
@php $id = 'f_'.$name; @endphp
<div class="mb-3">
    <label for="{{ $id }}" class="form-label">{{ $label }} @if ($required)<span class="text-danger">*</span>@endif</label>
    <input type="file" name="{{ $name }}" id="{{ $id }}" accept="{{ $accept }}" @if ($required && ! $current) required @endif
           class="form-control {{ $errors->has($name) ? 'is-invalid' : '' }}">
    @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
    <div class="form-text">{{ $help }}</div>
    @if ($current)
        <div class="small mt-1 d-flex gap-3 align-items-center flex-wrap">
            <a href="{{ media_url($current) }}" target="_blank" rel="noopener"><i class="bi bi-paperclip"></i> Lihat file saat ini</a>
            @if ($removable)
                <span class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" name="hapus_{{ $name }}" value="1" id="{{ $id }}_hapus">
                    <label class="form-check-label" for="{{ $id }}_hapus">Hapus file</label>
                </span>
            @endif
        </div>
    @endif
</div>
