@props(['name', 'label', 'value' => null, 'required' => false, 'help' => null, 'rows' => 3, 'col' => null])
@php $id = 'f_'.$name; @endphp
<div class="{{ $col ?? 'mb-3' }}">
    <label for="{{ $id }}" class="form-label">{{ $label }} @if ($required)<span class="text-danger">*</span>@endif</label>
    <textarea name="{{ $name }}" id="{{ $id }}" rows="{{ $rows }}" @if ($required) required @endif
        {{ $attributes->merge(['class' => 'form-control'.($errors->has($name) ? ' is-invalid' : '')]) }}>{{ old($name, $value) }}</textarea>
    @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
    @if ($help)<div class="form-text">{{ $help }}</div>@endif
</div>
