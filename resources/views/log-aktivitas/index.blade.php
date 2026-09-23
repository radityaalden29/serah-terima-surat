@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Log Aktivitas</h2>
        <p class="text-muted mb-0">Riwayat semua perubahan data surat dalam sistem.</p>
    </div>
    <a href="{{ route('log-aktivitas.export', request()->query()) }}" class="btn btn-soft-success">
        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
    </a>
</div>

{{-- Kartu Statistik --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-md-3">
        <div class="dashboard-card" style="border-left-color:#7b4fc7;">
            <div class="card-icon" style="background:#efe8ff;color:#7b4fc7;">
                <i class="bi bi-clock-history"></i>
            </div>
            <div>
                <small>Total Aktivitas</small>
                <h2 class="count-up" data-target="{{ $stats['total'] }}">0</h2>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="dashboard-card card-masuk">
            <div class="card-icon"><i class="bi bi-plus-circle"></i></div>
            <div>
                <small>Ditambahkan</small>
                <h2 class="count-up" data-target="{{ $stats['ditambahkan'] }}">0</h2>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="dashboard-card" style="border-left-color:#1971c2;">
            <div class="card-icon" style="background:#dbeafe;color:#1971c2;">
                <i class="bi bi-pencil"></i>
            </div>
            <div>
                <small>Diperbarui</small>
                <h2 class="count-up" data-target="{{ $stats['diperbarui'] }}">0</h2>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="dashboard-card card-keluar">
            <div class="card-icon"><i class="bi bi-trash"></i></div>
            <div>
                <small>Dihapus</small>
                <h2 class="count-up" data-target="{{ $stats['dihapus'] }}">0</h2>
            </div>
        </div>
    </div>

</div>

{{-- Filter --}}
<form method="GET" action="{{ route('log-aktivitas') }}" class="mb-4">
    <div class="row g-3">

        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Cari no agenda..."
                    value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-md-4">
            <select name="aksi" class="form-select">
                <option value="">Semua Aksi</option>
                <option value="Ditambahkan" {{ request('aksi') == 'Ditambahkan' ? 'selected' : '' }}>Ditambahkan</option>
                <option value="Diperbarui"  {{ request('aksi') == 'Diperbarui'  ? 'selected' : '' }}>Diperbarui</option>
                <option value="Dihapus"     {{ request('aksi') == 'Dihapus'     ? 'selected' : '' }}>Dihapus</option>
                <option value="Diimpor"     {{ request('aksi') == 'Diimpor'     ? 'selected' : '' }}>Diimpor</option>
            </select>
        </div>

        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            <a href="{{ route('log-aktivitas') }}" class="btn btn-ghost" title="Reset">
                <i class="bi bi-arrow-clockwise"></i>
            </a>
        </div>

    </div>
</form>

{{-- Tabel --}}
<div class="card shadow-sm border-0">

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width:50px;">#</th>
                        <th style="width:150px;">Aksi</th>
                        <th>No Agenda</th>
                        <th>Keterangan</th>
                        <th style="width:180px;">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        @php
                            $aksiKey = strtolower($log->aksi);
                            $aksiConfig = [
                                'ditambahkan' => ['icon' => 'bi-plus-circle-fill', 'class' => 'log-aksi-badge--ditambahkan'],
                                'diperbarui'  => ['icon' => 'bi-pencil-fill',       'class' => 'log-aksi-badge--diperbarui'],
                                'dihapus'     => ['icon' => 'bi-trash-fill',         'class' => 'log-aksi-badge--dihapus'],
                                'diimpor'     => ['icon' => 'bi-upload',             'class' => 'log-aksi-badge--diimpor'],
                            ];
                            $cfg = $aksiConfig[$aksiKey] ?? ['icon' => 'bi-circle', 'class' => ''];
                        @endphp
                        <tr style="animation-delay: {{ $loop->index * 0.03 }}s">
                            <td class="ps-4 text-muted">{{ $logs->firstItem() + $loop->index }}</td>
                            <td>
                                <span class="log-aksi-badge {{ $cfg['class'] }}">
                                    <i class="bi {{ $cfg['icon'] }}"></i>{{ $log->aksi }}
                                </span>
                            </td>
                            <td class="fw-semibold">{{ $log->no_agenda ?? '—' }}</td>
                            <td class="text-muted">{{ $log->keterangan ?? '—' }}</td>
                            <td class="text-muted" style="white-space:nowrap; font-size:14px;">
                                <i class="bi bi-clock me-1"></i>
                                {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="bi bi-clock-history"></i></div>
                                    <h5 class="empty-state-title">Belum Ada Log</h5>
                                    <p class="empty-state-text">
                                        @if(request('search') || request('aksi'))
                                            Tidak ada log yang cocok dengan filter yang dipilih.
                                        @else
                                            Aktivitas akan tercatat di sini saat ada perubahan data surat.
                                        @endif
                                    </p>
                                    @if(request('search') || request('aksi'))
                                        <a href="{{ route('log-aktivitas') }}" class="btn btn-ghost mt-2">
                                            <i class="bi bi-arrow-clockwise me-1"></i> Reset Filter
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($logs->hasPages())
    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center flex-wrap gap-2">
        <small class="text-muted">
            Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }} data
        </small>
        {{ $logs->links() }}
    </div>
    @endif

</div>

@endsection
