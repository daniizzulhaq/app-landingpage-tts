@props(['title', 'subtitle' => null, 'link' => null, 'linkText' => 'Lihat semua', 'tag' => 'h2'])
<div class="section-head">
    <div>
        <{{ $tag }} class="section-title">{{ $title }}</{{ $tag }}>
        @if ($subtitle)<p class="section-sub">{{ $subtitle }}</p>@endif
    </div>
    @if ($link)
        <a href="{{ $link }}" class="link-more">{{ $linkText }}</a>
    @endif
</div>
