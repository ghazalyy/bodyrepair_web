<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Akses Ditolak | Body Repair Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family:'Inter',sans-serif; background:#f1f5f9; display:flex; align-items:center; justify-content:center; min-height:100vh; }
        .error-box { text-align:center; padding:3rem 2rem; }
        .error-code { font-size:6rem; font-weight:800; color:#e2e8f0; line-height:1; }
        .error-icon { font-size:3rem; margin-bottom:1rem; }
    </style>
</head>
<body>
<div class="error-box">
    <div class="error-icon">🔒</div>
    <div class="error-code">403</div>
    <h2 class="fw-bold mt-2 mb-2" style="color:#0f172a">Akses Ditolak</h2>
    <p class="text-muted mb-4">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
    <a href="{{ url()->previous() }}" class="btn btn-outline-primary me-2">← Kembali</a>
    <a href="{{ route('dashboard') }}" class="btn btn-primary">Dashboard</a>
</div>
</body>
</html>
