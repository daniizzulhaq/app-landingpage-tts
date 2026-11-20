@extends('layouts.public')
@section('title', 'FAQ')
@section('description', 'Pertanyaan yang sering diajukan seputar layanan '.setting('site_name', 'Polres TTS').'.')

@if ($faqs->isNotEmpty())
    @push('meta')
        <script type="application/ld+json">{!! json_encode([
            '@context' => 'https://schema.org', '@type' => 'FAQPage',
            'mainEntity' => $faqs->map(fn ($f) => ['@type' => 'Question', 'name' => $f->pertanyaan,
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($f->jawaban)]])->all(),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
    @endpush
@endif

@section('content')
    <x-site.page-hero title="Pertanyaan yang Sering Diajukan" :crumbs="['Layanan' => null, 'FAQ' => null]" />
    <section class="section">
        <div class="container" style="max-width:880px">
            @if ($faqs->isEmpty())
                <x-site.empty message="Belum ada pertanyaan." icon="bi-question-circle" />
            @else
                <div class="accordion faq" id="faqAccordion">
                    @foreach ($faqs as $f)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button @class(['accordion-button', 'collapsed' => ! $loop->first]) type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $f->id }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="faq{{ $f->id }}">
                                    {{ $f->pertanyaan }}
                                </button>
                            </h2>
                            <div id="faq{{ $f->id }}" @class(['accordion-collapse collapse', 'show' => $loop->first]) data-bs-parent="#faqAccordion">
                                <div class="accordion-body prose">{!! $f->jawaban !!}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="info-box mt-5 d-flex flex-wrap gap-3 align-items-center">
                <i class="bi bi-chat-dots fs-3 text-warning"></i>
                <div class="flex-grow-1"><div class="fw-bold text-navy">Pertanyaan Anda belum terjawab?</div><div class="small text-muted">Hubungi kami melalui kontak pelayanan atau kanal pengaduan resmi.</div></div>
                <a href="{{ route('site.contact') }}" class="btn btn-navy">Hubungi Kami</a>
            </div>
        </div>
    </section>
@endsection
