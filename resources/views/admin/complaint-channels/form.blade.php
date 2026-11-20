@extends('layouts.admin')
@section('title', $channel->exists ? 'Ubah Kanal Pengaduan' : 'Tambah Kanal Pengaduan')

@section('content')
    <x-admin.page-header :title="$channel->exists ? 'Ubah Kanal Pengaduan' : 'Tambah Kanal Pengaduan'" :back="route('admin.complaint-channels.index')" />
    <div class="card" style="max-width:760px"><div class="card-body">
        <form method="POST" action="{{ $channel->exists ? route('admin.complaint-channels.update', $channel) : route('admin.complaint-channels.store') }}">
            @csrf
            @if ($channel->exists) @method('PUT') @endif
            <x-admin.input name="nama" label="Nama kanal" :value="$channel->nama" required />
            <x-admin.textarea name="deskripsi" label="Deskripsi" :value="$channel->deskripsi" rows="3" />
            <x-admin.input name="url" label="Tautan" :value="$channel->url" help="https://..., tel:110, atau mailto:..." />
            <div class="row">
                <x-admin.select name="ikon" label="Ikon" :options="App\Models\ComplaintChannel::ICONS" :value="$channel->ikon" required col="col-md-8 mb-3" />
                <x-admin.input type="number" name="urutan" label="Urutan" :value="$channel->urutan ?? 0" min="0" col="col-md-4 mb-3" />
            </div>
            <x-admin.form-actions :cancel="route('admin.complaint-channels.index')" />
        </form>
    </div></div>
@endsection
