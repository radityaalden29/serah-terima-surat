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
                        <div class="d-flex gap-2 mb-3">
                            <a href="{{ asset('storage/'.$surat->lampiran) }}"
                               target="_blank"
                               class="btn btn-soft-danger">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Buka di Tab Baru
                            </a>
                            <a href="{{ asset('storage/'.$surat->lampiran) }}"
                               download
                               class="btn btn-ghost">
                                <i class="bi bi-download me-1"></i> Unduh PDF
                            </a>
                        </div>

                        <div class="pdf-preview-wrapper" id="pdfWrapper">
                            <iframe
                                src="{{ asset('storage/'.$surat->lampiran) }}#toolbar=1&view=FitH"
                                title="Preview Lampiran PDF"
                                class="pdf-preview-frame"
                                id="pdfFrame"
                                loading="lazy">
                            </iframe>
                        </div>

                        {{-- Fallback: muncul jika iframe gagal load (browser mobile/tertentu) --}}
                        <div id="pdfFallback" class="d-none mt-3 p-4 text-center"
                             style="border:1px dashed var(--border-color);border-radius:14px;">
                            <i class="bi bi-file-earmark-pdf display-5 text-danger d-block mb-2"></i>
                            <p class="text-muted mb-3">Browser kamu tidak mendukung preview PDF inline.</p>
                            <a href="{{ asset('storage/'.$surat->lampiran) }}" target="_blank" class="btn btn-soft-danger">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Buka PDF
                            </a>
                        </div>
                    @else
                        <div class="d-flex align-items-center gap-3 p-4"
                             style="border:1px dashed var(--border-color);border-radius:14px;">
                            <i class="bi bi-paperclip fs-3 text-muted"></i>
                            <span class="text-muted">Tidak ada lampiran untuk surat ini.</span>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>
</div>

@push('scripts')
<script>
// Deteksi jika iframe PDF gagal dimuat (browser mobile/tertentu)
document.addEventListener('DOMContentLoaded', function () {
    const frame = document.getElementById('pdfFrame');
    const fallback = document.getElementById('pdfFallback');
    if (!frame || !fallback) return;

    // Timeout: jika iframe tidak merespons dalam 4 detik, tampilkan fallback
    const timer = setTimeout(function () {
        fallback.classList.remove('d-none');
    }, 4000);

    frame.addEventListener('load', function () {
        clearTimeout(timer);
        try {
            // Cek apakah konten benar-benar termuat (bukan halaman error)
            const doc = frame.contentDocument || frame.contentWindow.document;
            if (!doc || doc.title === '404' || doc.body?.innerHTML?.includes('not found')) {
                fallback.classList.remove('d-none');
            }
        } catch (e) {
            // Cross-origin — berarti PDF berhasil dimuat oleh browser
            clearTimeout(timer);
        }
    });

    frame.addEventListener('error', function () {
        clearTimeout(timer);
        fallback.classList.remove('d-none');
    });
});
</script>
@endpush

@endsection
