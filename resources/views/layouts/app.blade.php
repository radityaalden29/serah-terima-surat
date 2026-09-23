<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTERA | Sistem Informasi Serah Terima Surat</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo-sistera.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body>

<!-- Loading Screen -->
<div class="loading-screen" id="loadingScreen">
    <div class="loading-content">
        <img src="{{ asset('images/logo-sistera.png') }}" alt="Logo SISTERA" class="loading-logo">
        <div class="loading-spinner"></div>
        <p class="loading-text">Memuat SISTERA...</p>
    </div>
</div>

<div class="wrapper">

    <!-- Overlay (mobile sidebar backdrop) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">

      <div class="logo">

    <img src="{{ asset('images/logo-sistera.png') }}"
         alt="Logo SISTERA"
         class="logo-image">

</div>

        <ul class="menu">

            <li>

                <a href="{{ route('dashboard') }}"
                   class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">

                    <i class="bi bi-grid-fill"></i>

                    Dashboard

                </a>

            </li>

            <li>

                <a href="{{ route('surat.index') }}"
                   class="{{ request()->routeIs('surat.index','surat.show','surat.edit') ? 'active' : '' }}">

                    <i class="bi bi-folder-fill"></i>

                    Data Surat

                    @php
                        $suratDiterimaCount = \App\Models\Surat::where('status', 'Diterima')->count();
                    @endphp

                    @if($suratDiterimaCount > 0)
                        <span class="menu-badge">{{ $suratDiterimaCount }}</span>
                    @endif

                </a>

            </li>

            <li>

                <a href="{{ route('surat.create') }}"
                   class="{{ request()->routeIs('surat.create','surat.store') ? 'active' : '' }}">

                    <i class="bi bi-plus-circle-fill"></i>

                    Tambah Surat

                </a>

            </li>

            <li>

                <a href="{{ route('laporan') }}"
                   class="{{ request()->routeIs('laporan*') ? 'active' : '' }}">

                    <i class="bi bi-bar-chart-fill"></i>

                    Laporan

                </a>

            </li>

            <li>

                <a href="{{ route('log-aktivitas') }}"
                   class="{{ request()->routeIs('log-aktivitas') ? 'active' : '' }}">

                    <i class="bi bi-clock-history"></i>

                    Log Aktivitas

                </a>

            </li>

           <li>

    <a href="{{ route('tentang') }}"
       class="{{ request()->routeIs('tentang') ? 'active' : '' }}">

        <i class="bi bi-info-circle-fill"></i>

        Tentang

    </a>

</li>

        </ul>

    </aside>

    <!-- Main -->
    <main class="main-content">

        <!-- Topbar -->
        <header class="topbar">

            <div class="d-flex align-items-center gap-3">

                <button type="button" class="sidebar-toggle-btn" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>

                <div>
                    <h4 class="mb-0">
                        Sistem Informasi Serah Terima Surat
                    </h4>

                    <small class="text-muted">
                        Universitas Pakuan
                    </small>
                </div>

            </div>

            <div class="topbar-right d-flex align-items-center gap-3">

              <div class="date-box d-flex align-items-center gap-2">

    <i class="bi bi-calendar-event"></i>

    <span id="tanggal"></span>

    <span>|</span>

    <i class="bi bi-clock"></i>

    <span id="jam"></span>

</div>

            </div>

        </header>

        <section class="content">

            @php
                $breadcrumbs = [];

                if (request()->routeIs('surat.index')) {
                    $breadcrumbs = [
                        ['label' => 'Dashboard', 'icon' => 'bi-house-door-fill', 'url' => route('dashboard')],
                        ['label' => 'Data Surat', 'url' => null],
                    ];
                } elseif (request()->routeIs('surat.create')) {
                    $breadcrumbs = [
                        ['label' => 'Dashboard', 'icon' => 'bi-house-door-fill', 'url' => route('dashboard')],
                        ['label' => 'Data Surat', 'url' => route('surat.index')],
                        ['label' => 'Tambah Surat', 'url' => null],
                    ];
                } elseif (request()->routeIs('surat.edit')) {
                    $breadcrumbs = [
                        ['label' => 'Dashboard', 'icon' => 'bi-house-door-fill', 'url' => route('dashboard')],
                        ['label' => 'Data Surat', 'url' => route('surat.index')],
                        ['label' => 'Edit Surat', 'url' => null],
                    ];
                } elseif (request()->routeIs('surat.show')) {
                    $breadcrumbs = [
                        ['label' => 'Dashboard', 'icon' => 'bi-house-door-fill', 'url' => route('dashboard')],
                        ['label' => 'Data Surat', 'url' => route('surat.index')],
                        ['label' => 'Detail Surat', 'url' => null],
                    ];
                } elseif (request()->routeIs('laporan*')) {
                    $breadcrumbs = [
                        ['label' => 'Dashboard', 'icon' => 'bi-house-door-fill', 'url' => route('dashboard')],
                        ['label' => 'Laporan', 'url' => null],
                    ];
                } elseif (request()->routeIs('log-aktivitas')) {
                    $breadcrumbs = [
                        ['label' => 'Dashboard', 'icon' => 'bi-house-door-fill', 'url' => route('dashboard')],
                        ['label' => 'Log Aktivitas', 'url' => null],
                    ];
                } elseif (request()->routeIs('tentang')) {
                    $breadcrumbs = [
                        ['label' => 'Dashboard', 'icon' => 'bi-house-door-fill', 'url' => route('dashboard')],
                        ['label' => 'Tentang', 'url' => null],
                    ];
                }
            @endphp

            @if(count($breadcrumbs) > 0)
            <nav class="breadcrumb-nav mb-4" aria-label="breadcrumb">
                @foreach($breadcrumbs as $index => $crumb)

                    @if($index > 0)
                        <i class="bi bi-chevron-right breadcrumb-separator"></i>
                    @endif

                    @if($crumb['url'])
                        <a href="{{ $crumb['url'] }}" class="breadcrumb-item-link">
                            @if(!empty($crumb['icon']))<i class="bi {{ $crumb['icon'] }}"></i>@endif
                            {{ $crumb['label'] }}
                        </a>
                    @else
                        <span class="breadcrumb-item-current">{{ $crumb['label'] }}</span>
                    @endif

                @endforeach
            </nav>
            @endif

            @yield('content')

            <footer class="site-footer">
                <div class="site-footer__inner">

                    {{-- Brand --}}
                    <div class="site-footer__brand">
                        <div class="site-footer__brand-top">
                            <img src="{{ asset('images/logo-sistera.png') }}" alt="Logo SISTERA" class="site-footer__logo">
                            <div>
                                <div class="site-footer__name">SISTERA</div>
                                <div class="site-footer__tagline">Universitas Pakuan</div>
                            </div>
                        </div>
                        <p class="site-footer__desc">
                            Sistem Informasi Serah Terima Surat — pengelolaan surat masuk secara digital, cepat, dan terdokumentasi.
                        </p>
                    </div>

                    {{-- Link Groups --}}
                    <div class="site-footer__links">
                        <div class="site-footer__group">
                            <div class="site-footer__group-title">Menu Utama</div>
                            <a href="{{ route('dashboard') }}"    class="site-footer__link"><i class="bi bi-grid-fill"></i> Dashboard</a>
                            <a href="{{ route('surat.index') }}"  class="site-footer__link"><i class="bi bi-folder-fill"></i> Data Surat</a>
                            <a href="{{ route('surat.create') }}" class="site-footer__link"><i class="bi bi-plus-circle-fill"></i> Tambah Surat</a>
                        </div>
                        <div class="site-footer__group">
                            <div class="site-footer__group-title">Fitur Lain</div>
                            <a href="{{ route('laporan') }}"       class="site-footer__link"><i class="bi bi-bar-chart-fill"></i> Laporan</a>
                            <a href="{{ route('log-aktivitas') }}" class="site-footer__link"><i class="bi bi-clock-history"></i> Log Aktivitas</a>
                            <a href="{{ route('tentang') }}"       class="site-footer__link"><i class="bi bi-info-circle-fill"></i> Tentang</a>
                        </div>
                    </div>

                </div>

                <hr class="site-footer__divider">

                <div class="site-footer__bottom">
                    <span class="site-footer__copy">
                        © {{ date('Y') }} <strong>SISTERA</strong> &mdash; Sistem Informasi Serah Terima Surat
                    </span>
                    <span class="site-footer__version">
                        <i class="bi bi-tag-fill"></i> v1.5
                    </span>
                </div>
            </footer>

        </section>

    </main>

</div>

@if(session('success'))

<script>
const suceessToast = Swal.mixin({
    toast:true,
    position:'top-end',
    showConfirmButton:false,
    timer:3000,
    timerProgressBar:true,
    didOpen:(toast)=>{
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});
suceessToast.fire({
    icon:'success',
    title:'{{ session('success') }}'
});
</script>

@endif

@if(session('error'))

<script>
const errorToast = Swal.mixin({
    toast:true,
    position:'top-end',
    showConfirmButton:false,
    timer:3500,
    timerProgressBar:true,
    didOpen:(toast)=>{
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});
errorToast.fire({
    icon:'error',
    title:'{{ session('error') }}'
});
</script>

@endif

@stack('scripts')

<script>
document.addEventListener("DOMContentLoaded", function () {

    // Sembunyikan loading screen setelah halaman siap
    const loadingScreen = document.getElementById('loadingScreen');
    if (loadingScreen) {
        window.addEventListener('load', function () {
            setTimeout(function () {
                loadingScreen.classList.add('loading-hidden');
                setTimeout(function () {
                    loadingScreen.remove();
                }, 500);
            }, 400);
        });
    }

    function updateClock() {

        const now = new Date();

        const tanggal = now.toLocaleDateString('id-ID', {
            weekday: 'long',
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });

        const jam = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });

        document.getElementById('tanggal').textContent = tanggal;
        document.getElementById('jam').textContent = jam;
    }

    updateClock();
    setInterval(updateClock, 1000);

    // Toggle sidebar (mobile)
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('sidebarToggle');

    function closeSidebar() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    }

    toggleBtn.addEventListener('click', function () {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    });

    overlay.addEventListener('click', closeSidebar);

    // ===== Sapaan dinamis sesuai jam =====
    const greetingEl = document.getElementById('dynamicGreeting');
    if (greetingEl) {
        const hour = new Date().getHours();
        let greeting = 'Selamat malam';

        if (hour >= 4 && hour < 11) {
            greeting = 'Selamat pagi';
        } else if (hour >= 11 && hour < 15) {
            greeting = 'Selamat siang';
        } else if (hour >= 15 && hour < 19) {
            greeting = 'Selamat sore';
        }

        greetingEl.textContent = greeting;
    }

    // ===== Counting-up animation untuk angka statistik =====
    document.querySelectorAll('.count-up').forEach(function (el) {

        const target = parseInt(el.getAttribute('data-target'), 10) || 0;
        const duration = 900;
        const startTime = performance.now();

        function tick(now) {
            const progress = Math.min((now - startTime) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);

            const nilai = Math.floor(eased * target);
            el.textContent = nilai.toLocaleString('id-ID');

            if (progress < 1) {
                requestAnimationFrame(tick);
            } else {
                el.textContent = target.toLocaleString('id-ID');
            }
        }

        requestAnimationFrame(tick);
    });

});
</script>

</body>
</html>
