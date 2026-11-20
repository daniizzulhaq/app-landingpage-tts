@extends('layouts.admin')
@section('title', 'Pratinjau '.App\Models\Post::typeLabel($type))

@section('content')
    <x-admin.page-header :title="'Pratinjau '.App\Models\Post::typeLabel($type)" :back="url()->previous() !== url()->current() ? url()->previous() : route('admin.posts.index', $type)">
        <x-admin.status :model="$post" />
        @can('update', $post)
            <a href="{{ route('admin.posts.edit', [$type, $post]) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil"></i> Ubah</a>
        @endcan
    </x-admin.page-header>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card"><div class="card-body p-md-4">
                <h2 class="h4">{{ $post->judul }}</h2>
                <div class="text-muted small mb-3">
                    {{ $post->unit?->name ?? 'Polres TTS' }} · {{ $post->author?->name }} · {{ tgl($post->displayDate(), 'D MMMM YYYY HH:mm') }}
                </div>
                @if ($post->thumbnail)
                    <img src="{{ media_url($post->thumbnail) }}" class="img-fluid rounded mb-3" alt="">
                @endif
                @if ($post->ringkasan)<p class="lead fs-6 fw-semibold">{{ $post->ringkasan }}</p>@endif
                <div class="content-body">{!! $post->isi !!}</div>
                @if ($post->images->isNotEmpty())
                    <div class="photo-grid mt-3">
                        @foreach ($post->images as $img)
                            <div class="photo-item"><img src="{{ thumb_url($img->path) }}" alt="{{ $img->caption }}"><div class="small p-2">{{ $img->caption }}</div></div>
                        @endforeach
                    </div>
                @endif
                @if ($post->lampiran)
                    <a href="{{ media_url($post->lampiran) }}" target="_blank" class="btn btn-light mt-3"><i class="bi bi-paperclip"></i> Lampiran</a>
                @endif
            </div></div>
        </div>
        <div class="col-lg-4">
            <div class="card"><div class="card-body small">
                <dl class="mb-0">
                    <dt>Kategori</dt><dd>{{ $post->category?->name ?? '-' }}</dd>
                    @if ($post->tanggal_kegiatan)<dt>Tanggal kegiatan</dt><dd>{{ tgl($post->tanggal_kegiatan) }}</dd>@endif
                    @if ($post->lokasi)<dt>Lokasi</dt><dd>{{ $post->lokasi }}</dd>@endif
                    <dt>Dibuat</dt><dd>{{ tgl($post->created_at, 'D MMM YYYY HH:mm') }}</dd>
                    @if ($post->approver)<dt>Disetujui oleh</dt><dd>{{ $post->approver->name }}</dd>@endif
                    @if ($post->catatan_revisi)<dt>Catatan revisi</dt><dd class="text-danger" style="white-space:pre-line">{{ $post->catatan_revisi }}</dd>@endif
                </dl>
            </div></div>
            @if ($post->status === 'pending' && auth()->user()->isSuperAdmin())
                @include('admin.approval._actions', ['approve' => route('admin.approval.posts.approve', $post), 'reject' => route('admin.approval.posts.reject', $post), 'id' => 'p'.$post->id])
            @endif
        </div>
    </div>
@endsection
