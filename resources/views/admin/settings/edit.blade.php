@extends('layouts.admin')
@section('title', 'Pengaturan')

@section('content')
    <x-admin.page-header title="Pengaturan Situs" />
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row g-3">
            @foreach ($groups as $group => $fields)
                <div class="col-xl-6">
                    <div class="card h-100">
                        <div class="card-header">{{ $group }}</div>
                        <div class="card-body">
                            @foreach ($fields as $key => $f)
                                @php $val = $values[$key] ?? null; @endphp
                                @switch($f['type'])
                                    @case('image')
                                        <x-admin.image :name="$key" :label="$f['label']" :current="$val" />
                                        @break
                                    @case('boolean')
                                        <x-admin.checkbox :name="$key" :label="$f['label']" :checked="$val === '1'"
                                                          help="Jika dimatikan, konten Admin Unit langsung tayang tanpa persetujuan." />
                                        @break
                                    @case('textarea')
                                        <x-admin.textarea :name="$key" :label="$f['label']" :value="$val" rows="3" :help="$f['help'] ?? null" />
                                        @break
                                    @case('editor')
                                        <x-admin.editor :name="$key" :label="$f['label']" :value="$val" height="180" />
                                        @break
                                    @default
                                        <x-admin.input :type="$f['type']" :name="$key" :label="$f['label']" :value="$val" :help="$f['help'] ?? null" />
                                @endswitch
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="form-actions">
            <button class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Simpan Pengaturan</button>
        </div>
    </form>
@endsection
