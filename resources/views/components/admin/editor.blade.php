@props(['name', 'label', 'value' => null, 'required' => false, 'help' => null, 'height' => 280])
@php $id = 'f_'.$name; @endphp
@once
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    @endpush
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    @endpush
@endonce
<div class="mb-3">
    <label class="form-label" for="{{ $id }}">{{ $label }} @if ($required)<span class="text-danger">*</span>@endif</label>
    <div class="editor-wrap {{ $errors->has($name) ? 'is-invalid border-danger' : '' }}">
        <div class="quill-editor" data-input="{{ $id }}" style="min-height: {{ $height }}px"></div>
    </div>
    <textarea name="{{ $name }}" id="{{ $id }}" class="d-none" @if ($required) data-required="1" @endif>{{ old($name, $value) }}</textarea>
    @error($name)<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    @if ($help)<div class="form-text">{{ $help }}</div>@endif
</div>
