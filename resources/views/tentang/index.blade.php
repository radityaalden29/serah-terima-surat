@extends('layouts.app')

@section('content')

<div class="mb-4">
    <h2 class="fw-bold mb-1">Tentang SISTERA</h2>
    <p class="text-muted mb-0">Informasi umum mengenai aplikasi ini.</p>
</div>

<div class="card form-card shadow-sm">
    <div class="card-body p-4">

        <div class="about-section">
            <div class="form-section-title">
                <span class="icon-badge"><i class="bi bi-info-circle"></i></span>
                SISTERA
            </div>
            <p>
                SISTERA (Sistem Informasi Serah Terima Surat) merupakan website yang digunakan
                untuk mengelola proses serah terima surat masuk secara digital.
            </p>
        </div>

        <hr class="form-divider">

        <div class="about-section">
            <div class="form-section-title">
                <span class="icon-badge"><i class="bi bi-stars"></i></span>
                Fitur Sistem
            </div>

            <div class="feature-list">
                <div class="feature-item"><i class="bi bi-check2-circle"></i> Dashboard</div>
                <div class="feature-item"><i class="bi bi-check2-circle"></i> Tambah Surat</div>
                <div class="feature-item"><i class="bi bi-check2-circle"></i> Data Surat</div>
                <div class="feature-item"><i class="bi bi-check2-circle"></i> Edit Surat</div>
                <div class="feature-item"><i class="bi bi-check2-circle"></i> Hapus Surat</div>
                <div class="feature-item"><i class="bi bi-check2-circle"></i> Laporan</div>
                <div class="feature-item"><i class="bi bi-check2-circle"></i> Statistik Surat</div>
            </div>
        </div>

        <hr class="form-divider">

        <div class="about-section">
            <div class="form-section-title">
                <span class="icon-badge"><i class="bi bi-cpu"></i></span>
                Teknologi
            </div>

            <div class="tech-grid">
                <div class="tech-item">
                    <i class="bi bi-boxes"></i>
                    <div>
                        <span class="tech-label">Framework</span>
                        <span class="tech-value">Laravel 12</span>
                    </div>
                </div>

                <div class="tech-item">
                    <i class="bi bi-palette"></i>
                    <div>
                        <span class="tech-label">CSS</span>
                        <span class="tech-value">Bootstrap 5</span>
                    </div>
                </div>

                <div class="tech-item">
                    <i class="bi bi-database"></i>
                    <div>
                        <span class="tech-label">Database</span>
                        <span class="tech-value">SQLite</span>
                    </div>
                </div>

                <div class="tech-item">
                    <i class="bi bi-bar-chart-line"></i>
                    <div>
                        <span class="tech-label">Chart</span>
                        <span class="tech-value">Chart.js</span>
                    </div>
                </div>
            </div>
        </div>

        <hr class="form-divider">

        <div class="about-section">
            <div class="form-section-title">
                <span class="icon-badge"><i class="bi bi-clock-history"></i></span>
                Riwayat Pembaruan
            </div>

            <div class="timeline">

                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <span class="timeline-version">v1.3</span>
                        <span class="timeline-date">Terbaru</span>
                        <p>Mode gelap, breadcrumb navigasi, notifikasi toast, dan animasi interaktif di berbagai halaman.</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <span class="timeline-version">v1.2</span>
                        <p>Tampilan visual diperbarui: warna tema, tipografi, dan ukuran teks yang lebih nyaman dibaca.</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <span class="timeline-version">v1.1</span>
                        <p>Penambahan halaman Laporan, ekspor PDF &amp; Excel, serta pencarian data surat secara langsung (live search).</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <span class="timeline-version">v1.0</span>
                        <p>Rilis pertama SISTERA: pencatatan surat masuk, dashboard statistik, dan manajemen data dasar.</p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection
