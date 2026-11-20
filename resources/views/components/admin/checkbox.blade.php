@props(['name', 'label', 'checked' => false, 'help' => null])
@php $id = 'f_'.$name; @endphp
<div class="mb-3">
    <div class="form-check form-switch">
        <input type="hidden" name="{{ $name }}" value="0">
        <input class="form-check-input" type="checkbox" role="switch" name="{{ $name }}" id="{{ $id }}" value="1" @checked(old($name, $checked))>
        <label class="form-check-label" for="{{ $id }}">{{ $label }}</label>
    </div>
    @if ($help)<div class="form-text">{{ $help }}</div>@endif
</div>
