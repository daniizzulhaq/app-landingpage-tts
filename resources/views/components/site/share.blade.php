@props(['url', 'title'])
@php
    $u = urlencode($url);
    $t = urlencode($title);
@endphp
<div class="share">
    <span class="label">Bagikan:</span>
    <a href="https://wa.me/?text={{ $t }}%20{{ $u }}" target="_blank" rel="noopener" aria-label="Bagikan ke WhatsApp"><i class="bi bi-whatsapp"></i></a>
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $u }}" target="_blank" rel="noopener" aria-label="Bagikan ke Facebook"><i class="bi bi-facebook"></i></a>
    <a href="https://twitter.com/intent/tweet?url={{ $u }}&text={{ $t }}" target="_blank" rel="noopener" aria-label="Bagikan ke X"><i class="bi bi-twitter-x"></i></a>
    <button type="button" data-copy="{{ $url }}" aria-label="Salin tautan"><i class="bi bi-link-45deg"></i></button>
</div>
