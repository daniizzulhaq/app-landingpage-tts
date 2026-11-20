@props(['message' => 'Belum ada data.', 'icon' => 'bi-inbox'])
<div {{ $attributes->merge(['class' => 'empty-state']) }}>
    <i class="bi {{ $icon }}" aria-hidden="true"></i>
    <div>{{ $message }}</div>
    {{ $slot }}
</div>
