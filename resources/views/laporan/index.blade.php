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

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Dari Tanggal</label>
                    <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Sampai Tanggal</label>
                    <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
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
                        <td colspan="6" class="text-center text-muted">Belum ada data laporan surat.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
