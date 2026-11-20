@extends('layouts.public')
@section('title', $archive ? 'Arsip Agenda' : 'Agenda')
@section('description', 'Jadwal kegiatan dan agenda '.setting('site_name', 'Polres TTS').'.')

@section('content')
    <x-site.page-hero title="Agenda" :crumbs="['Publikasi' => null, 'Agenda' => null]" subtitle="Jadwal kegiatan mendatang dan arsip agenda." />
    <section class="section">
        <div class="container" style="max-width:900px">
            <nav class="tabs-line" aria-label="Jenis agenda">
                <a href="{{ route('site.agenda') }}" @class(['active' => ! $archive]) @if (! $archive) aria-current="page" @endif>Mendatang</a>
                <a href="{{ route('site.agenda', ['arsip' => 1]) }}" @class(['active' => $archive]) @if ($archive) aria-current="page" @endif>Arsip</a>
            </nav>
            @forelse ($agendas as $ag)
                @include('site.partials.agenda-item', ['agenda' => $ag, 'full' => true])
            @empty
                <x-site.empty :message="$archive ? 'Belum ada arsip agenda.' : 'Belum ada agenda mendatang.'" icon="bi-calendar-x" />
            @endforelse
            <div class="mt-4 d-flex justify-content-center">{{ $agendas->links() }}</div>
        </div>
    </section>
@endsection
