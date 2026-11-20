@extends('layouts.admin')
@section('title', $faq->exists ? 'Ubah FAQ' : 'Tambah FAQ')

@section('content')
    <x-admin.page-header :title="$faq->exists ? 'Ubah FAQ' : 'Tambah FAQ'" :back="route('admin.faqs.index')" />
    <div class="card" style="max-width:860px"><div class="card-body">
        <form method="POST" action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}">
            @csrf
            @if ($faq->exists) @method('PUT') @endif
            <x-admin.input name="pertanyaan" label="Pertanyaan" :value="$faq->pertanyaan" required />
            <x-admin.editor name="jawaban" label="Jawaban" :value="$faq->jawaban" required height="200" />
            <x-admin.input type="number" name="urutan" label="Urutan" :value="$faq->urutan ?? 0" min="0" style="max-width:160px" />
            <x-admin.form-actions :cancel="route('admin.faqs.index')" />
        </form>
    </div></div>
@endsection
