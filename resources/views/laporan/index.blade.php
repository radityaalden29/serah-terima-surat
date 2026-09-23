@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Laporan Surat</h2>
        <p class="text-muted mb-0">Menampilkan laporan surat berdasarkan rentang tanggal.</p>
    </div>
</div>

<form method="GET" action="{{ route('laporan') }}">

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Dari Tanggal</label>
                    <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Sampai Tanggal</label>
                    <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Pengirim</label>
                    <input type="text" name="pengirim" class="form-control" list="listPengirim"
                           placeholder="Semua pengirim" value="{{ request('pengirim') }}">
                    <datalist id="listPengirim">
                        @foreach($daftarPengirim as $p)
                            <option value="{{ $p }}">
                        @endforeach
                    </datalist>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Penerima</label>
                    <input type="text" name="penerima" class="form-control" list="listPenerima"
                           placeholder="Semua penerima" value="{{ request('penerima') }}">
                    <datalist id="listPenerima">
                        @foreach($daftarPenerima as $p)
                            <option value="{{ $p }}">
                        @endforeach
                    </datalist>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="Selesai"  {{ request('status') == 'Selesai'  ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <div class="col-12">
                    <div class="laporan-action">

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel-fill me-1"></i> Filter
                        </button>

                        <a href="{{ route('laporan.cetak', request()->query()) }}" class="btn btn-soft-danger">
                            <i class="bi bi-printer-fill me-1"></i> Cetak PDF
                        </a>

                        <a href="{{ route('laporan.excel') }}" class="btn btn-soft-success">
                            <i class="bi bi-file-earmark-excel me-1"></i> Excel
                        </a>

                        <a href="{{ route('laporan') }}" class="btn btn-ghost">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reset
                        </a>

                    </div>
                </div>

            </div>

        </div>
    </div>

</form>

@php
    if (!function_exists('suratAvatarColor')) {
        function suratAvatarColor($name) {
            $colors = ['#7b4fc7','#2f9e44','#e8590c','#1971c2','#c2255c','#0ca678','#f08c00','#5f3dc4'];
            $hash = 0;
            foreach (str_split((string) $name) as $char) {
                $hash = ord($char) + (($hash << 5) - $hash);
            }
            return $colors[abs($hash) % count($colors)];
        }
    }

    if (!function_exists('suratInitials')) {
        function suratInitials($name) {
            $words = preg_split('/\s+/', trim((string) $name));
            $initials = mb_strtoupper(mb_substr($words[0] ?? '', 0, 1));
            if (count($words) > 1) {
                $initials .= mb_strtoupper(mb_substr(end($words), 0, 1));
            }
            return $initials !== '' ? $initials : '?';
        }
    }
@endphp

<div class="row g-4 mb-4">

    <div class="col-lg-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-graph-up-arrow me-1"></i> Tren Surat 12 Bulan Terakhir</h5>
            </div>
            <div class="card-body">
                <canvas id="chartTrenBulanan" height="220"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-building me-1"></i> Instansi Paling Sering Kirim Surat</h5>
            </div>
            <div class="card-body">

                @forelse($statistikInstansi as $item)
                    <div class="ranking-item">
                        <div class="ranking-label">
                            <span class="avatar-circle" style="background:{{ suratAvatarColor($item->pengirim) }}">
                                {{ suratInitials($item->pengirim) }}
                            </span>
                            <span>{{ $item->pengirim }}</span>
                        </div>
                        <div class="ranking-bar-wrapper">
                            <div class="ranking-bar" style="width:{{ ($item->jumlah / $maxInstansi) * 100 }}%"></div>
                        </div>
                        <span class="ranking-count">{{ number_format($item->jumlah, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <div class="empty-state" style="padding:30px 20px;">
                        <div class="empty-state-icon sm"><i class="bi bi-building"></i></div>
                        <p class="empty-state-text mb-0">Belum ada data instansi.</p>
                    </div>
                @endforelse

            </div>
        </div>
    </div>

</div>

<div class="card shadow border-0">

    <div class="card-header bg-white">
        <h5 class="mb-0">Data Laporan Surat</h5>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No Agenda</th>
                        <th>Jenis</th>
                        <th>Pengirim</th>
                        <th>Penerima</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($surats as $surat)
                    <tr>
                        <td>{{ $surat->no_agenda }}</td>
                        <td>{{ $surat->jenis }}</td>
                        <td>{{ $surat->pengirim }}</td>
                        <td>{{ $surat->penerima }}</td>
                        <td>
                            @if($surat->status == 'Selesai')
                                <span class="status-pill status-pill--selesai">
                                    <i class="bi bi-check2-all"></i> {{ $surat->status }}
                                </span>
                            @else
                                <span class="status-pill status-pill--diterima">
                                    <i class="bi bi-check-circle-fill"></i> {{ $surat->status }}
                                </span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($surat->tanggal)->format('d-m-Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-0">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="bi bi-funnel"></i></div>
                                <h5 class="empty-state-title">Tidak Ada Hasil</h5>
                                <p class="empty-state-text">Tidak ada surat yang cocok dengan filter yang dipilih.</p>
                                <a href="{{ route('laporan') }}" class="btn btn-ghost mt-2">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Reset Filter
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const canvas = document.getElementById('chartTrenBulanan');
    if (!canvas || !window.Chart) return;

    new window.Chart(canvas, {
        type: 'bar',
        data: {
            labels: @json($labelBulan),
            datasets: [{
                label: 'Jumlah Surat',
                data: @json($dataBulan),
                backgroundColor: '#7b4fc7',
                borderRadius: 6,
                maxBarThickness: 32
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1000,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });

});
</script>
@endpush
