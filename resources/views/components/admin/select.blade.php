@props(['name', 'label', 'options' => [], 'value' => null, 'required' => false, 'placeholder' => null, 'help' => null, 'col' => null, 'disabled' => false])
@php
    $id = 'f_'.$name;
    $selected = (string) old($name, $value);
@endphp
<div class="{{ $col ?? 'mb-3' }}">
    <label for="{{ $id }}" class="form-label">{{ $label }} @if ($required)<span class="text-danger">*</span>@endif</label>
    <select name="{{ $name }}" id="{{ $id }}" @if ($required) required @endif @if ($disabled) disabled @endif
        {{ $attributes->merge(['class' => 'form-select'.($errors->has($name) ? ' is-invalid' : '')]) }}>
        @if ($placeholder !== null)<option value="">{{ $placeholder }}</option>@endif
        @foreach ($options as $key => $text)
            <option value="{{ $key }}" @selected($selected === (string) $key)>{{ $text }}</option>
        @endforeach
    </select>
    @if ($disabled)
        <input type="hidden" name="{{ $name }}" value="{{ $selected }}">
    @endif
    @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
    @if ($help)<div class="form-text">{{ $help }}</div>@endif
</div>
