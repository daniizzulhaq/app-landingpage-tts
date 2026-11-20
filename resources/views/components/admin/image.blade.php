@props(['name', 'label', 'current' => null, 'removable' => true, 'help' => 'Format JPG, PNG, atau WebP. Maks 5 MB.', 'accept' => 'image/jpeg,image/png,image/webp', 'required' => false])
@php $id = 'f_'.$name; @endphp
<div class="mb-3">
    <label for="{{ $id }}" class="form-label">{{ $label }} @if ($required)<span class="text-danger">*</span>@endif</label>
    <div class="d-flex gap-3 align-items-start">
        <img src="{{ thumb_url($current) }}" alt="" class="img-preview rounded border" data-preview-for="{{ $id }}">
        <div class="flex-grow-1">
            <input type="file" name="{{ $name }}" id="{{ $id }}" accept="{{ $accept }}" @if ($required && ! $current) required @endif
                   class="form-control {{ $errors->has($name) ? 'is-invalid' : '' }}" data-preview>
            @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="form-text">{{ $help }}</div>
            @if ($current && $removable)
                <div class="form-check mt-1">
                    <input class="form-check-input" type="checkbox" name="hapus_{{ $name }}" value="1" id="{{ $id }}_hapus">
                    <label class="form-check-label small" for="{{ $id }}_hapus">Hapus gambar saat ini</label>
                </div>
            @endif
        </div>
    </div>
</div>
