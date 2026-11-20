@props(['title', 'subtitle' => null, 'back' => null])
<div class="d-flex flex-wrap align-items-center gap-2 mb-3">
    <div class="me-auto">
        <h1 class="h4 mb-0">
            @if ($back)<a href="{{ $back }}" class="text-decoration-none text-muted me-1" aria-label="Kembali"><i class="bi bi-arrow-left"></i></a>@endif
            {{ $title }}
        </h1>
        @if ($subtitle)<div class="text-muted small">{{ $subtitle }}</div>@endif
    </div>
    {{ $slot }}
</div>
