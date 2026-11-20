<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Masuk · Admin {{ setting('site_name', 'Polres TTS') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}?v=1" rel="stylesheet">
</head>
<body class="admin">
<div class="auth-page">
    <div class="card auth-card shadow">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <img src="{{ media_url(setting('logo'), 'img/logo-placeholder.svg') }}" alt="Logo" width="64" height="64" class="mb-2" style="object-fit:contain">
                <h1 class="h5 mb-0">{{ setting('site_name', 'Polres TTS') }}</h1>
                <div class="text-muted small">Panel Administrasi</div>
            </div>
            @include('admin.partials.flash')
            <form method="POST" action="{{ route('admin.login.store') }}" novalidate>
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" required autofocus autocomplete="username">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" name="password" id="password" class="form-control" required autocomplete="current-password">
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button class="btn btn-primary w-100 py-2">Masuk</button>
            </form>
            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="small text-muted text-decoration-none"><i class="bi bi-arrow-left"></i> Kembali ke situs</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
