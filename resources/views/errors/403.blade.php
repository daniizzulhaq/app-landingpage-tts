<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Akses ditolak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body class="admin">
<div class="auth-page">
    <div class="card auth-card text-center"><div class="card-body p-5">
        <div class="display-4 fw-bold" style="color:var(--gold)">403</div>
        <h1 class="h5">Akses ditolak</h1>
        <p class="text-muted small">Anda tidak memiliki izin untuk membuka halaman ini.</p>
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}" class="btn btn-primary">Kembali</a>
    </div></div>
</div>
</body>
</html>
