@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Edit Surat</h2>
        <p class="text-muted mb-0">Ubah data serah terima surat.</p>
    </div>

    <a href="{{ route('surat.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card form-card shadow-sm">
    <div class="card-body p-4">

        <form action="{{ route('surat.update',$surat->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-section-title">
                <span class="icon-badge"><i class="bi bi-file-earmark-text"></i></span>
                Informasi Surat
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label fw-semibold">No Agenda</label>
                    <input
                        type="text"
                        name="no_agenda"
                        class="form-control @error('no_agenda') is-invalid @enderror"
                        value="{{ old('no_agenda',$surat->no_agenda) }}">
                    @error('no_agenda')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jenis Surat</label>
                    <div class="info-box">
                        <span class="status-pill status-pill--jenis">
                            <i class="bi bi-inbox-fill"></i> Surat Masuk
                        </span>
                        <span class="info-box-note">
                            Sistem ini sekarang hanya mencatat Surat Masuk.
                        </span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pengirim</label>
                    <input
                        type="text"
                        name="pengirim"
                        class="form-control @error('pengirim') is-invalid @enderror"
                        value="{{ old('pengirim',$surat->pengirim) }}">
                    @error('pengirim')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Penerima</label>
                    <input
                        type="text"
                        name="penerima"
                        class="form-control @error('penerima') is-invalid @enderror"
                        value="{{ old('penerima',$surat->penerima) }}">
                    @error('penerima')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Perihal</label>
                    <textarea
                        name="perihal"
                        rows="3"
                        class="form-control @error('perihal') is-invalid @enderror">{{ old('perihal',$surat->perihal) }}</textarea>
                    @error('perihal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <hr class="form-divider">

            <div class="form-section-title">
                <span class="icon-badge"><i class="bi bi-gear"></i></span>
                Detail Tambahan
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal</label>
                    <input
                        type="date"
                        name="tanggal"
                        class="form-control @error('tanggal') is-invalid @enderror"
                        value="{{ old('tanggal',$surat->tanggal) }}">
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status</label>
                    <div class="info-box">
                        @if($surat->status == 'Selesai')
                            <span class="status-pill status-pill--selesai">
                                <i class="bi bi-check2-all"></i> Selesai
                            </span>
                        @else
                            <span class="status-pill status-pill--diterima">
                                <i class="bi bi-check-circle-fill"></i> Diterima
                            </span>
                        @endif
                        <span class="info-box-note">
                            Status diatur otomatis oleh sistem berdasarkan tanggal surat, bukan input manual.
                        </span>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Lampiran (PDF)</label>
                    <input
                        type="file"
                        name="lampiran"
                        class="form-control @error('lampiran') is-invalid @enderror"
                        accept=".pdf">
                    @error('lampiran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    @if($surat->lampiran)
                        <small class="text-success d-block mt-2">
                            Lampiran saat ini :
                            <a href="{{ asset('storage/'.$surat->lampiran) }}" target="_blank">Lihat PDF</a>
                        </small>
                    @endif
                </div>

            </div>

            <hr class="form-divider">

            <div class="form-actions">
                <a href="{{ route('surat.index') }}" class="btn btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary" id="btnUpdate">
                    <i class="bi bi-check-circle"></i> Update Surat
                </button>
            </div>

        </form>

    </div>
</div>

@push('scripts')
<script>
document.querySelector('form').addEventListener('submit', function () {

    const btn = document.getElementById('btnUpdate');

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...';

});
</script>
@endpush

@endsection
