@extends('layouts.app')

@section('content')

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Data Surat</h2>
        <p class="text-muted mb-0">Daftar seluruh data serah terima surat.</p>
    </div>

    <a href="{{ route('surat.create') }}" class="btn-tambah">
        <i class="bi bi-plus-circle-fill"></i>
        <span>Tambah Surat</span>
    </a>
</div>

<form action="{{ route('surat.index') }}" method="GET" class="mb-4">

    <!-- Baris Pencarian -->
    <div class="input-group mb-3">
        <span class="input-group-text">
            <i class="bi bi-search"></i>
        </span>

        <input
            type="text"
            class="form-control"
            name="search"
            placeholder="Cari nomor agenda, pengirim atau penerima..."
            value="{{ request('search') }}">

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-search me-1"></i> Cari
        </button>
    </div>

    <!-- Filter -->
    <div class="row g-3">

        <div class="col-md-4">
            <label class="form-label fw-semibold">Status</label>
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-semibold">Dari Tanggal</label>
            <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
        </div>

        <div class="col-md-3">
            <label class="form-label fw-semibold">Sampai Tanggal</label>
            <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
        </div>

        <div class="col-md-2 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            <a href="{{ route('surat.index') }}" class="btn btn-secondary" title="Reset filter">
                <i class="bi bi-arrow-clockwise"></i>
            </a>
        </div>

    </div>

</form>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <div id="tabel-surat">
            @include('surat._table')
        </div>

    </div>
</div>

@push('scripts')
<script>

// Konfirmasi hapus — pakai event delegation biar tetap jalan
// walau baris tabelnya dimuat ulang lewat live search (AJAX)
document.getElementById('tabel-surat').addEventListener('submit', function (e) {

    const form = e.target.closest('.delete-form');
    if (!form) return;

    e.preventDefault();

    Swal.fire({
        title: 'Hapus Surat?',
        text: 'Data yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#7b4fc7',
        cancelButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});

// Live search — auto cari sambil ngetik, tanpa reload halaman
(function () {

    const searchInput = document.querySelector('input[name="search"]');
    const tabelSurat = document.getElementById('tabel-surat');
    const filterForm = searchInput.closest('form');

    if (!searchInput || !tabelSurat || !filterForm) return;

    let timer = null;

    // Bikin 5 baris skeleton (shimmer) sesuai jumlah kolom tabel
    function skeletonHTML() {
        let rows = '';
        for (let i = 0; i < 5; i++) {
            rows += `
                <tr class="skeleton-row">
                    <td><div class="skeleton-line" style="width:20px;"></div></td>
                    <td><div class="skeleton-line" style="width:110px;"></div></td>
                    <td><div class="skeleton-line" style="width:90px;"></div></td>
                    <td><div class="skeleton-avatar-line"><span class="skeleton-avatar"></span><div class="skeleton-line" style="width:80px;"></div></div></td>
                    <td><div class="skeleton-avatar-line"><span class="skeleton-avatar"></span><div class="skeleton-line" style="width:80px;"></div></div></td>
                    <td><div class="skeleton-line" style="width:90px;"></div></td>
                    <td><div class="skeleton-line" style="width:80px; border-radius:999px;"></div></td>
                    <td><div class="skeleton-line" style="width:70px; margin:0 auto;"></div></td>
                </tr>`;
        }

        return `
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th><th>No Agenda</th><th>Jenis</th><th>Pengirim</th>
                            <th>Penerima</th><th>Tanggal</th><th>Status</th><th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>`;
    }

    searchInput.addEventListener('input', function () {

        clearTimeout(timer);

        timer = setTimeout(function () {

            const formData = new FormData(filterForm);
            const params = new URLSearchParams(formData);

            tabelSurat.innerHTML = skeletonHTML();

            fetch(filterForm.action + '?' + params.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                tabelSurat.innerHTML = html;

                // Update URL browser tanpa reload, biar bisa di-refresh/bookmark
                history.pushState(null, '', filterForm.action + '?' + params.toString());
            })
            .catch(() => {
                tabelSurat.innerHTML = '<p class="text-center text-muted py-4">Gagal memuat data. Coba lagi.</p>';
            });

        }, 450); // jeda 450ms biar nggak nembak request tiap huruf

    });

    // Klik nomor halaman pagination juga lewat AJAX, bukan reload penuh
    tabelSurat.addEventListener('click', function (e) {

        const link = e.target.closest('.pagination a');
        if (!link) return;

        e.preventDefault();

        tabelSurat.innerHTML = skeletonHTML();

        fetch(link.href, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            tabelSurat.innerHTML = html;
            history.pushState(null, '', link.href);
        });

    });

})();

</script>
@endpush

@endsection
