<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak | SISTERA</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo-sistera.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
</head>
<body>

<div class="error-page">
    <div class="error-box">

        <svg class="error-illustration" viewBox="0 0 220 180" xmlns="http://www.w3.org/2000/svg">
            <ellipse cx="110" cy="160" rx="70" ry="10" fill="#efe8ff"/>

            <g class="error-illustration-float">
                <rect x="70" y="80" width="80" height="65" rx="10" fill="#7b4fc7"/>
                <path d="M85 80 V60 a25 25 0 0 1 50 0 V80" stroke="#5f3aa0" stroke-width="8" fill="none" stroke-linecap="round"/>
                <circle cx="110" cy="108" r="9" fill="#fff"/>
                <rect x="106" y="108" width="8" height="16" rx="4" fill="#fff"/>
            </g>

            <circle cx="175" cy="45" r="14" fill="#9b74e0"/>
            <text x="175" y="51" text-anchor="middle" font-size="16" font-weight="800" fill="#fff" font-family="Poppins, sans-serif">!</text>

            <circle cx="35" cy="110" r="7" fill="#c3b0eb"/>
            <circle cx="185" cy="130" r="5" fill="#c3b0eb"/>
        </svg>

        <img src="{{ asset('images/logo-sistera.png') }}" alt="Logo SISTERA" class="error-logo">

        <div class="code">403</div>
        <h3>Akses Ditolak</h3>
        <p>Kamu tidak memiliki izin untuk mengakses halaman ini.</p>

        <a href="{{ route('dashboard') }}" class="btn btn-primary">
            <i class="bi bi-house-door"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

</body>
</html>
