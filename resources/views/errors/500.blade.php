<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terjadi Kesalahan | SISTERA</title>

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
                <rect x="55" y="60" width="110" height="75" rx="10" fill="#7b4fc7"/>
                <line x1="55" y1="80" x2="165" y2="80" stroke="#5f3aa0" stroke-width="4"/>
                <circle cx="68" cy="70" r="4" fill="#fff"/>
                <circle cx="82" cy="70" r="4" fill="#fff"/>
                <circle cx="96" cy="70" r="4" fill="#fff"/>
                <path d="M95 95 l15 15 m0 -15 l-15 15" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
                <circle cx="145" cy="102" r="12" fill="none" stroke="#fff" stroke-width="4"/>
            </g>

            <circle cx="180" cy="45" r="14" fill="#9b74e0"/>
            <text x="180" y="51" text-anchor="middle" font-size="16" font-weight="800" fill="#fff" font-family="Poppins, sans-serif">!</text>

            <circle cx="30" cy="115" r="7" fill="#c3b0eb"/>
            <circle cx="190" cy="130" r="5" fill="#c3b0eb"/>
        </svg>

        <img src="{{ asset('images/logo-sistera.png') }}" alt="Logo SISTERA" class="error-logo">

        <div class="code">500</div>
        <h3>Terjadi Kesalahan pada Server</h3>
        <p>Maaf, ada yang tidak beres di sisi kami. Coba muat ulang halaman beberapa saat lagi.</p>

        <a href="{{ route('dashboard') }}" class="btn btn-primary">
            <i class="bi bi-house-door"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

</body>
</html>
