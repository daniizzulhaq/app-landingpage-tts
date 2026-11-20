@extends('layouts.public')
@section('title', $q ? 'Hasil pencarian "'.$q.'"' : 'Pencarian')
@push('meta')<meta name="robots" content="noindex, follow">@endpush

@section('content')
    <x-site.page-hero title="Pencarian" :crumbs="['Pencarian' => null]" />
    <section class="section">
        <div class="container" style="max-width:900px">
            <form action="{{ route('site.search') }}" method="GET" role="search" class="mb-4">
                <div class="input-group input-group-lg">
                    <input type="search" name="q" value="{{ $q }}" class="form-control" placeholder="Cari berita, layanan, atau dokumen..." aria-label="Kata kunci" minlength="2" required>
                    <button class="btn btn-navy"><i class="bi bi-search"></i><span class="visually-hidden">Cari</span></button>
                </div>
            </form>

            @if (mb_strlen($q) < 2)
                <x-site.empty message="Masukkan minimal 2 karakter untuk mencari." icon="bi-search" />
            @elseif ($results->isEmpty())
                <x-site.empty :message="'Tidak ditemukan hasil untuk “'.$q.'”. Coba kata kunci lain.'" icon="bi-search" />
            @else
                <p class="text-muted mb-4">Ditemukan <strong>{{ $total }}</strong> hasil untuk "<strong>{{ $q }}</strong>".</p>
                @php $mark = fn ($text) => preg_replace('/('.preg_quote(e($q), '/').')/iu', '<mark>$1</mark>', e($text)); @endphp
                @foreach ($results as $group)
                    <div class="search-group">
                        <h2><i class="bi {{ $group['icon'] }} text-warning"></i>{{ $group['label'] }} <span class="badge text-bg-light">{{ $group['items']->count() }}</span></h2>
                        @foreach ($group['items'] as $item)
                            <div class="search-result">
                                <a href="{{ $item['url'] }}" class="t" @if (! empty($item['download'])) target="_blank" rel="noopener" @endif>{!! $mark($item['title']) !!}</a>
                                <div class="small text-muted">{{ $item['meta'] }}</div>
                                @if ($item['text'])<p class="mb-0 mt-1 small">{!! $mark(Str::limit($item['text'], 200)) !!}</p>@endif
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @endif
        </div>
    </section>
@endsection
