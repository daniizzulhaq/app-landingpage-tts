@extends('layouts.admin')
@php $super = auth()->user()->isSuperAdmin(); @endphp
@section('title', $agenda->exists ? 'Ubah Agenda' : 'Tambah Agenda')

@section('content')
    <x-admin.page-header :title="$agenda->exists ? 'Ubah Agenda' : 'Tambah Agenda'" :back="route('admin.agendas.index')">
        @if ($agenda->exists)<x-admin.status :model="$agenda" />@endif
    </x-admin.page-header>

    @if ($agenda->status === 'rejected' && $agenda->catatan_revisi)
        <div class="alert alert-warning"><div class="fw-semibold">Catatan revisi dari Super Admin:</div><div style="white-space:pre-line">{{ $agenda->catatan_revisi }}</div></div>
    @endif

    <div class="card" style="max-width:860px"><div class="card-body">
        <form method="POST" action="{{ $agenda->exists ? route('admin.agendas.update', $agenda) : route('admin.agendas.store') }}">
            @csrf
            @if ($agenda->exists) @method('PUT') @endif
            <x-admin.input name="judul" label="Nama agenda" :value="$agenda->judul" required />
            <div class="row">
                <x-admin.input type="datetime-local" name="mulai" label="Mulai" :value="optional($agenda->mulai)->format('Y-m-d\TH:i')" required col="col-md-6 mb-3" />
                <x-admin.input type="datetime-local" name="selesai" label="Selesai (opsional)" :value="optional($agenda->selesai)->format('Y-m-d\TH:i')" col="col-md-6 mb-3" />
            </div>
            <x-admin.input name="lokasi" label="Lokasi" :value="$agenda->lokasi" />
            <x-admin.textarea name="deskripsi" label="Deskripsi" :value="$agenda->deskripsi" rows="4" />
            @if ($super)
                <x-admin.select name="unit_id" label="Unit" :options="$units" :value="$agenda->unit_id" placeholder="— Polres (umum) —" />
            @else
                <div class="mb-3"><label class="form-label">Unit</label>
                    <select class="form-select" disabled aria-label="Unit"><option>{{ auth()->user()->unit?->name }}</option></select>
                    <div class="form-text"><i class="bi bi-lock"></i> Terkunci ke unit Anda.</div></div>
            @endif
            <div class="form-actions">
                <a href="{{ route('admin.agendas.index') }}" class="btn btn-light">Batal</a>
                <button type="submit" name="action" value="draft" class="btn btn-outline-primary"><i class="bi bi-save me-1"></i>Simpan Draft</button>
                <button type="submit" name="action" value="submit" class="btn btn-primary">
                    @if ($canPublish)<i class="bi bi-send-check me-1"></i>Simpan & Tayangkan @else<i class="bi bi-send me-1"></i>Kirim untuk Persetujuan @endif
                </button>
            </div>
        </form>
    </div></div>
    @if ($agenda->exists && $agenda->status === 'pending' && $super)
        <div style="max-width:860px">
            @include('admin.approval._actions', ['approve' => route('admin.approval.agendas.approve', $agenda), 'reject' => route('admin.approval.agendas.reject', $agenda), 'id' => 'g'.$agenda->id])
        </div>
    @endif
@endsection
