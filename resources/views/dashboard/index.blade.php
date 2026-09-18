@extends('layouts.app')

@section('content')

<div class="dashboard-header mb-5">
    <div>
        <h1 class="fw-bold mb-2">Dashboard</h1>
        <p class="text-muted fs-5">
            <span id="dynamicGreeting">Selamat datang</span> di <strong>SISTERA</strong>.
            Kelola seluruh proses serah terima surat secara cepat, aman dan terdokumentasi.
        </p>
    </div>

    <div>
        <a href="{{ route('surat.create') }}" class="btn btn-tambah shadow">
            <i class="bi bi-plus-circle"></i>
            <span>Tambah Surat</span>
        </a>
    </div>
</div>

<div class="row g-4 mb-5">

    <div class="col-lg-4 col-md-6">
        <div class="dashboard-card card-total">
            <div class="card-icon"><i class="bi bi-envelope-paper"></i></div>
            <div>
                <small>Total Surat</small>
                <h2 class="count-up" data-target="{{ $totalSurat }}">0</h2>
            </div>
            <div class="card-info-row">
                <span class="card-info">Semua data surat masuk</span>
                <span class="trend-badge trend-badge--{{ $trenTotal >= 0 ? 'up' : 'down' }}">
                    <i class="bi bi-arrow-{{ $trenTotal >= 0 ? 'up' : 'down' }}-short"></i>
                    {{ abs($trenTotal) }}%
                </span>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="dashboard-card card-masuk">
            <div class="card-icon"><i class="bi bi-check-circle"></i></div>
            <div>
                <small>Diterima</small>
                <h2 class="count-up" data-target="{{ $diterima }}">0</h2>
            </div>
            <div class="card-info-row">
                <span class="card-info">Belum melewati tanggal surat</span>
                <span class="trend-badge trend-badge--{{ $trenDiterima >= 0 ? 'up' : 'down' }}">
                    <i class="bi bi-arrow-{{ $trenDiterima >= 0 ? 'up' : 'down' }}-short"></i>
                    {{ abs($trenDiterima) }}%
                </span>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="dashboard-card card-menunggu">
            <div class="card-icon"><i class="bi bi-check2-all"></i></div>
            <div>
                <small>Selesai</small>
                <h2 class="count-up" data-target="{{ $selesai }}">0</h2>
            </div>
            <div class="card-info-row">
                <span class="card-info">Sudah melewati tanggal surat</span>
                <span class="trend-badge trend-badge--{{ $trenSelesai >= 0 ? 'up' : 'down' }}">
                    <i class="bi bi-arrow-{{ $trenSelesai >= 0 ? 'up' : 'down' }}-short"></i>
                    {{ abs($trenSelesai) }}%
                </span>
            </div>
        </div>
    </div>

</div>

<div class="row g-4">

    <div class="col-lg-8">
        <div class="card form-card shadow-sm h-100">
            <div class="card-body p-4">

                <div class="form-section-title">
                    <span class="icon-badge"><i class="bi bi-pie-chart"></i></span>
                    Statistik Surat
                </div>

                <div style="height:300px;">
                    <canvas id="chartSurat"></canvas>
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card form-card shadow-sm h-100">
            <div class="card-body p-4">

                <div class="form-section-title">
                    <span class="icon-badge"><i class="bi bi-clipboard-data"></i></span>
                    Ringkasan
                </div>

                <div class="ringkasan-item">
                    <span class="label"><span class="dot dot-total"></span> Total Surat</span>
                    <span class="value count-up" data-target="{{ $totalSurat }}">0</span>
                </div>

                <div class="ringkasan-item">
                    <span class="label"><span class="dot dot-masuk"></span> Diterima</span>
                    <span class="value count-up" data-target="{{ $diterima }}">0</span>
                </div>

                <div class="ringkasan-item">
                    <span class="label"><span class="dot dot-menunggu"></span> Selesai</span>
                    <span class="value count-up" data-target="{{ $selesai }}">0</span>
                </div>

            </div>
        </div>
    </div>

</div>

<div class="card form-card shadow-sm mt-4">
    <div class="card-body p-4">

        <div class="form-section-title">
            <span class="icon-badge"><i class="bi bi-clock-history"></i></span>
            Aktivitas Terbaru
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>No Agenda</th>
                        <th>Jenis</th>
                        <th>Pengirim</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($surats as $surat)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $surat->no_agenda }}</td>
                        <td>{{ $surat->jenis }}</td>
                        <td>{{ $surat->pengirim }}</td>
                        <td>
                            @if($surat->status == 'Selesai')
                                <span class="status-pill status-pill--selesai">
                                    <i class="bi bi-check2-all"></i> Selesai
                                </span>
                            @else
                                <span class="status-pill status-pill--diterima">
                                    <i class="bi bi-check-circle-fill"></i> Diterima
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-0">
                            <div class="empty-state">
                                <div class="empty-state-icon sm"><i class="bi bi-inbox"></i></div>
                                <h5 class="empty-state-title">Belum Ada Aktivitas</h5>
                                <p class="empty-state-text">Surat yang baru ditambahkan akan muncul di sini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const canvas = document.getElementById('chartSurat');
    if (!canvas) return;

    new window.Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: ['Diterima', 'Selesai'],
            datasets: [{
                data: [{{ $diterima }}, {{ $selesai }}],
                backgroundColor: ['#7b4fc7', '#8c98a4'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                animateScale: true,
                animateRotate: true,
                duration: 1200,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

});
</script>
@endpush

@endsection
