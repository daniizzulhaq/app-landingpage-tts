@extends('layouts.admin')
@section('title', $user->exists ? 'Ubah Pengguna' : 'Tambah Pengguna')

@section('content')
    <x-admin.page-header :title="$user->exists ? 'Ubah Pengguna' : 'Tambah Pengguna'" :back="route('admin.users.index')" />

    <div class="card" style="max-width:720px">
        <div class="card-body">
            <form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}">
                @csrf
                @if ($user->exists) @method('PUT') @endif
                <div class="row">
                    <x-admin.input name="name" label="Nama" :value="$user->name" required col="col-md-6 mb-3" />
                    <x-admin.input type="email" name="email" label="Email" :value="$user->email" required col="col-md-6 mb-3" autocomplete="off" />
                </div>
                <div class="row">
                    <x-admin.select name="role" label="Role" :options="App\Models\User::ROLES" :value="$role" required col="col-md-6 mb-3"
                                    :disabled="$user->is(auth()->user())" />
                    <div class="col-md-6" id="unit-wrap">
                        <x-admin.select name="unit_id" label="Unit" :options="$units->mapWithKeys(fn ($u) => [$u->id => $u->name.' ('.$u->typeLabel().')'])->all()"
                                        :value="$user->unit_id" placeholder="— Pilih unit —" help="Wajib untuk Admin Unit." />
                    </div>
                </div>
                <div class="row">
                    <x-admin.input type="password" name="password" label="Kata sandi" :required="! $user->exists" col="col-md-6 mb-3"
                                   :help="$user->exists ? 'Kosongkan jika tidak diubah.' : 'Minimal 8 karakter, huruf dan angka.'" autocomplete="new-password" />
                    <x-admin.input type="password" name="password_confirmation" label="Ulangi kata sandi" :required="! $user->exists" col="col-md-6 mb-3" autocomplete="new-password" />
                </div>
                @unless ($user->is(auth()->user()))
                    <x-admin.checkbox name="is_active" label="Akun aktif" :checked="$user->is_active" />
                @endunless
                <x-admin.form-actions :cancel="route('admin.users.index')" />
            </form>
        </div>
    </div>
@endsection
