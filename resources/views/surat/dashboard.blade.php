@extends('layouts.app')

@section('content')
@if(session('success'))

<div class="alert alert-success">

{{ session('success') }}

</div>

@endif

{{-- HERO BARU --}}
<div class="card border-0 shadow-sm mb-4">

<div class="card-body">

<h2 class="fw-bold">

Selamat Datang 👋

</h2>

<p class="text-secondary">

Kelola data serah terima surat dengan cepat,
aman, dan efisien.

</p>

</div>

</div>

<div class="row g-4">

<div class="col-lg-3 col-md-6">

<div class="card bg-primary text-white">

<div class="card-body">

<i class="bi bi-envelope-paper-fill display-5"></i>

<h6 class="mt-3">

Total Surat

</h6>

<h2>

{{ $totalSurat }}

</h2>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card bg-success text-white">

<div class="card-body">

<i class="bi bi-box-arrow-in-down display-5"></i>

<h6 class="mt-3">

Surat Masuk

</h6>

<h2>

{{ $suratMasuk }}

</h2>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card bg-warning text-white">

<div class="card-body">

<i class="bi bi-box-arrow-up display-5"></i>

<h6 class="mt-3">

Surat Keluar

</h6>

<h2>

{{ $suratKeluar }}

</h2>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card bg-danger text-white">

<div class="card-body">

<i class="bi bi-hourglass-split display-5"></i>

<h6 class="mt-3">

Menunggu

</h6>

<h2>

{{ $menunggu }}

</h2>

</div>

</div>

</div>

</div>


<div class="card mt-4 shadow">

    <div class="card-header text-white" style="background:#7b4fc7;">

        Daftar Surat

    </div>

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h5 class="fw-bold mb-0">
                Data Surat
            </h5>

            <form method="GET">

                <div class="input-group">

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Cari surat...">

                    <button class="btn btn-primary">

                        <i class="bi bi-search"></i>

                    </button>

                </div>

            </form>

        </div>

        <table class="table table-bordered table-hover">

    <div class="card-body">

        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>No Agenda</th>
                    <th>Jenis</th>
                    <th>Pengirim</th>
                    <th>Penerima</th>
                    <th>Status</th>
                    <th>Aksi</th>

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

@if($surat->status=="Menunggu")

<span class="badge bg-warning">
Menunggu
</span>

@elseif($surat->status=="Diterima")

<span class="badge bg-success">
Diterima
</span>

@else

<span class="badge bg-primary">
Dikirim
</span>

@endif

</td>

<td>

<a href="#"
class="btn btn-info btn-sm">

Detail

</a>

<a href="#"
class="btn btn-warning btn-sm">

Edit

</a>

<button
class="btn btn-danger btn-sm">

Hapus

</button>

</td>

</tr>

@empty

<tr>

<td colspan="6" class="text-center">
    Belum ada data surat
</td>

</tr>

@endforelse

</tbody>
        </table>

    </div>

</div>

@endsection
