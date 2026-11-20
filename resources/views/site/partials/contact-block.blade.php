@php $socials = collect(config('site.social_icons'))->filter(fn ($i, $k) => setting($k)); @endphp
<div class="row g-4 align-items-stretch">
    <div class="col-lg-5">
        <div class="{{ ! empty($dark) ? 'contact-dark' : 'sidebar-card h-100' }}">
            <ul class="contact-list">
                <li><span class="ic"><i class="bi bi-geo-alt"></i></span><div><div class="lbl">Alamat</div><div class="val">{{ setting('alamat', '(diisi admin)') }}</div></div></li>
                <li><span class="ic"><i class="bi bi-telephone"></i></span><div><div class="lbl">Telepon</div>
                    @if (setting('telepon'))<a href="{{ tel_link(setting('telepon')) }}" class="val">{{ setting('telepon') }}</a>@else<div class="val">(diisi admin)</div>@endif
                    <div class="small" style="opacity:.75">Darurat: <a href="tel:110" class="fw-bold" style="color:inherit">110</a></div></div></li>
                @if (setting('whatsapp'))
                    <li><span class="ic"><i class="bi bi-whatsapp"></i></span><div><div class="lbl">WhatsApp</div><a href="https://wa.me/{{ preg_replace('/\D/', '', setting('whatsapp')) }}" target="_blank" rel="noopener" class="val">{{ setting('whatsapp') }}</a></div></li>
                @endif
                <li><span class="ic"><i class="bi bi-envelope"></i></span><div><div class="lbl">Email</div>
                    @if (setting('email'))<a href="mailto:{{ setting('email') }}" class="val">{{ setting('email') }}</a>@else<div class="val">(diisi admin)</div>@endif</div></li>
                <li><span class="ic"><i class="bi bi-clock"></i></span><div><div class="lbl">Jam Pelayanan</div><div class="val" style="white-space:pre-line">{{ setting('jam_pelayanan', '(diisi admin)') }}</div></div></li>
            </ul>
            @if ($socials->isNotEmpty())
                <div class="d-flex gap-2 mt-3">
                    @foreach ($socials as $key => $icon)
                        <a href="{{ setting($key) }}" target="_blank" rel="noopener" class="btn {{ ! empty($dark) ? 'btn-outline-light-soft' : 'btn-outline-navy' }} btn-sm" aria-label="{{ ucfirst($key) }}"><i class="bi {{ $icon }}"></i></a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <div class="col-lg-7">
        <div class="map-frame">
            @if (setting('map_embed'))
                <iframe src="{{ setting('map_embed') }}" title="Peta lokasi {{ setting('site_name', 'Polres TTS') }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
            @else
                <div class="map-empty"><div><i class="bi bi-map fs-1 d-block mb-2"></i>Peta lokasi belum tersedia.<br><small>Silakan hubungi kami untuk informasi lokasi pelayanan.</small></div></div>
            @endif
        </div>
        @if (setting('map_link'))
            <a href="{{ setting('map_link') }}" target="_blank" rel="noopener" class="btn btn-navy mt-3"><i class="bi bi-sign-turn-right me-1"></i>Petunjuk Arah</a>
        @endif
    </div>
</div>
