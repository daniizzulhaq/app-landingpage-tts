@extends('layouts.admin')
@section('title', 'Ganti Kata Sandi')

@section('content')
    <x-admin.page-header title="Ganti Kata Sandi" />
    <div class="card" style="max-width:520px">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.password.update') }}">
                @csrf
                @method('PUT')
                <x-admin.input type="password" name="current_password" label="Kata sandi lama" required autocomplete="current-password" />
                <x-admin.input type="password" name="password" label="Kata sandi baru" required autocomplete="new-password" help="Minimal 8 karakter, mengandung huruf dan angka." />
                <x-admin.input type="password" name="password_confirmation" label="Ulangi kata sandi baru" required autocomplete="new-password" />
                <x-admin.form-actions :cancel="route('admin.dashboard')" label="Simpan Kata Sandi" />
            </form>
        </div>
    </div>
@endsection
