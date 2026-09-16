@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Detail Surat</h2>
        <p class="text-muted mb-0">Informasi lengkap data surat.</p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('surat.edit',$surat->id) }}" class="btn btn-soft-warning">
            <i class="bi bi-pencil-square"></i> Edit
        </a>
        <a href="{{ route('surat.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card form-card shadow-sm">
    <div class="card-body p-4">

        <div class="form-section-title">
            <span class="icon-badge"><i class="bi bi-file-earmark-text"></i></span>
            Data Surat
        </div>

        <div class="detail-list">

            <div class="detail-row">
                <div class="detail-label">No Agenda</div>
                <div class="detail-value">{{ $surat->no_agenda }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Jenis Surat</div>
                <div class="detail-value">
                    <span class="status-pill status-pill--jenis">
                        <i class="bi bi-inbox-fill"></i> {{ $surat->jenis }}
                    </span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Pengirim</div>
                <div class="detail-value">{{ $surat->pengirim }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Penerima</div>
                <div class="detail-value">{{ $surat->penerima }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Perihal</div>
                <div class="detail-value">{{ $surat->perihal }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Tanggal</div>
                <div class="detail-value">{{ \Carbon\Carbon::parse($surat->tanggal)->translatedFormat('d F Y') }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    @if($surat->status == 'Selesai')
                        <span class="status-pill status-pill--selesai">
                            <i class="bi bi-check2-all"></i> Selesai
                        </span>
                    @else
                        <span class="status-pill status-pill--diterima">
                            <i class="bi bi-check-circle-fill"></i> Diterima
                        </span>
                    @endif
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Lampiran</div>
                <div class="detail-value w-100">
                    @if($surat->lampiran)
                        <a href="{{ asset('storage/'.$surat->lampiran) }}" target="_blank" class="btn btn-soft-danger mb-3">
                            <i class="bi bi-box-arrow-up-right"></i> Buka di Tab Baru
                        </a>

                        <div class="pdf-preview-wrapper">
                            <iframe
                                src="{{ asset('storage/'.$surat->lampiran) }}"
                                title="Preview Lampiran PDF"
                                class="pdf-preview-frame"
                                loading="lazy">
                            </iframe>
                        </div>
                    @else
                        <span class="text-muted">Tidak ada lampiran.</span>
                    @endif
                </div>
            </div>

        </div>

    </div>
</div>

@endsection
