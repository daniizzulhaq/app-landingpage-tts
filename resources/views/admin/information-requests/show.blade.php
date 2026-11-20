@extends('layouts.admin')
@section('title', 'Detail Permohonan')

@section('content')
    <x-admin.page-header title="Detail Permohonan Informasi" :back="route('admin.information-requests.index')">
        <span class="status-badge status-{{ $item->status }}">{{ $item->statusLabel() }}</span>
    </x-admin.page-header>
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card"><div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Nama pemohon</dt><dd class="col-sm-8">{{ $item->nama }}</dd>
                    <dt class="col-sm-4">Kontak</dt><dd class="col-sm-8">{{ $item->kontak }}</dd>
                    <dt class="col-sm-4">Tanggal masuk</dt><dd class="col-sm-8">{{ tgl($item->created_at, 'dddd, D MMMM YYYY HH:mm') }} WITA</dd>
                    <dt class="col-sm-4">Informasi diminta</dt><dd class="col-sm-8" style="white-space:pre-line">{{ $item->informasi_diminta }}</dd>
                    <dt class="col-sm-4">Tujuan penggunaan</dt><dd class="col-sm-8" style="white-space:pre-line">{{ $item->tujuan }}</dd>
                </dl>
            </div></div>
        </div>
        <div class="col-lg-4">
            <div class="card"><div class="card-header">Tindak lanjut</div><div class="card-body">
                <form method="POST" action="{{ route('admin.information-requests.update', $item) }}">
                    @csrf
                    @method('PUT')
                    <x-admin.select name="status" label="Status" :options="App\Models\InformationRequest::STATUSES" :value="$item->status" required />
                    <x-admin.textarea name="catatan" label="Catatan internal" :value="$item->catatan" rows="4" />
                    <button class="btn btn-primary w-100">Simpan</button>
                </form>
            </div></div>
        </div>
    </div>
@endsection
