@php $past = $agenda->mulai->lt(now()->startOfDay()) && (! $agenda->selesai || $agenda->selesai->isPast()); @endphp
<article @class(['agenda-item', 'past' => $past])>
    <div class="agenda-date" aria-hidden="true">
        <span class="d">{{ $agenda->mulai->format('d') }}</span>
        <span class="m">{{ tgl($agenda->mulai, 'MMM YY') }}</span>
    </div>
    <div class="min-w-0">
        <h3 class="t h6 mb-1">{{ $agenda->judul }}</h3>
        <div class="meta"><i class="bi bi-clock me-1"></i>{{ $agenda->waktu() }}</div>
        @if ($agenda->lokasi)<div class="meta"><i class="bi bi-geo-alt me-1"></i>{{ $agenda->lokasi }}</div>@endif
        @if (! empty($full) && $agenda->deskripsi)<p class="small text-secondary mt-2 mb-0" style="white-space:pre-line">{{ $agenda->deskripsi }}</p>@endif
        @if (! empty($full) && $agenda->unit)<div class="meta mt-1"><i class="bi bi-diagram-3 me-1"></i>{{ $agenda->unit->name }}</div>@endif
    </div>
</article>
