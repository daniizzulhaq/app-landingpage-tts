@props(['post', 'showType' => false])
<a href="{{ $post->url() }}" class="card-clean">
    <div class="card-media">
        <img src="{{ thumb_url($post->thumbnail) }}" alt="" loading="lazy" decoding="async">
    </div>
    <div class="card-body-clean">
        @if ($showType)
            <div class="card-tag">{{ $post->label() }}</div>
        @elseif ($post->category)
            <div class="card-tag">{{ $post->category->name }}</div>
        @endif
        <h3 class="card-title-clean">{{ $post->judul }}</h3>
        <div class="card-meta mt-auto pt-1">
            <span><i class="bi bi-calendar3"></i>{{ tgl($post->type === 'kegiatan' && $post->tanggal_kegiatan ? $post->tanggal_kegiatan : $post->displayDate(), 'D MMM YYYY') }}</span>
            @if ($post->type === 'kegiatan' && $post->lokasi)
                <span><i class="bi bi-geo-alt"></i>{{ Str::limit($post->lokasi, 30) }}</span>
            @elseif ($post->unit)
                <span><i class="bi bi-diagram-3"></i>{{ $post->unit->name }}</span>
            @endif
        </div>
        <span class="card-read-more">Baca selengkapnya <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span>
    </div>
</a>
