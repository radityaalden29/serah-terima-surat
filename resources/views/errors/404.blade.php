<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan | SISTERA</title>

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
                <rect x="45" y="45" width="130" height="90" rx="10" fill="#7b4fc7"/>
                <path d="M45 55 L110 100 L175 55" stroke="#fff" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                <rect x="45" y="45" width="130" height="90" rx="10" fill="none" stroke="#5f3aa0" stroke-width="3"/>
            </g>

            <circle cx="170" cy="35" r="16" fill="#9b74e0"/>
            <text x="170" y="41" text-anchor="middle" font-size="18" font-weight="800" fill="#fff" font-family="Poppins, sans-serif">?</text>

            <circle cx="30" cy="120" r="8" fill="#c3b0eb"/>
            <circle cx="195" cy="110" r="5" fill="#c3b0eb"/>
        </svg>

        <img src="{{ asset('images/logo-sistera.png') }}" alt="Logo SISTERA" class="error-logo">

        <div class="code">404</div>
        <h3>Halaman Tidak Ditemukan</h3>
        <p>Halaman yang kamu cari tidak tersedia atau sudah dipindahkan.</p>

        <a href="{{ route('dashboard') }}" class="btn btn-primary">
            <i class="bi bi-house-door"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

</body>
</html>
