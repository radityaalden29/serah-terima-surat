@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold">Tambah Surat</h2>
        <p class="text-muted mb-0">Isi data serah terima surat.</p>
    </div>

    <a href="{{ route('surat.index') }}" class="btn btn-secondary">
        Kembali
    </a>
</div>

<div class="card form-card shadow-sm">
    <div class="card-body p-4">

        <form action="{{ route('surat.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

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
                        placeholder="Contoh: 143/FH-UP/VII/2026"
                        value="{{ old('no_agenda') }}">
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
                        placeholder="Nama instansi / unit pengirim"
                        value="{{ old('pengirim') }}">
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
                        placeholder="Nama instansi / unit penerima"
                        value="{{ old('penerima') }}">
                    @error('penerima')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Perihal</label>
                    <textarea
                        name="perihal"
                        class="form-control @error('perihal') is-invalid @enderror"
                        rows="3"
                        placeholder="Ringkasan isi surat">{{ old('perihal') }}</textarea>
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
                        value="{{ old('tanggal') }}">
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status</label>
                    <div class="info-box">
                        <span class="status-pill status-pill--diterima">
                            <i class="bi bi-check-circle-fill"></i> Diterima
                        </span>
                        <span class="info-box-note">
                            Status diatur otomatis oleh sistem dan akan berubah menjadi
                            <strong>Selesai</strong> dengan sendirinya setelah tanggal surat di atas terlewati.
                        </span>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Lampiran PDF</label>
                    <input
                        type="file"
                        name="lampiran"
                        class="form-control @error('lampiran') is-invalid @enderror"
                        accept=".pdf">
                    @error('lampiran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <hr class="form-divider">

            <div class="form-actions">
                <a href="{{ route('surat.index') }}" class="btn btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary" id="btnSimpan">
                    <i class="bi bi-check-circle"></i> Simpan Surat
                </button>
            </div>

        </form>

    </div>
</div>

@push('scripts')
<script>
(function () {
    const form = document.querySelector('form');
    let formDirty = false;
    let formSubmitting = false;

    // Tandai form sudah diubah saat ada input apapun
    form.addEventListener('input', () => { formDirty = true; });
    form.addEventListener('change', () => { formDirty = true; });

    // Saat submit — jangan tampilkan dialog
    form.addEventListener('submit', function () {
        formSubmitting = true;
        const btn = document.getElementById('btnSimpan');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...';
    });

    // Klik tombol Batal / Kembali
    document.querySelectorAll('a[href]').forEach(function (link) {
        link.addEventListener('click', function (e) {
            if (!formDirty || formSubmitting) return;
            e.preventDefault();
            const target = this.href;
            Swal.fire({
                title: 'Tinggalkan halaman?',
                text: 'Perubahan yang belum disimpan akan hilang.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, tinggalkan',
                cancelButtonText: 'Tetap di sini',
                confirmButtonColor: '#7b4fc7',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    formDirty = false;
                    window.location.href = target;
                }
            });
        });
    });

    // Peringatan browser-native saat refresh/tutup tab
    window.addEventListener('beforeunload', function (e) {
        if (formDirty && !formSubmitting) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
})();
</script>
@endpush

@endsection
