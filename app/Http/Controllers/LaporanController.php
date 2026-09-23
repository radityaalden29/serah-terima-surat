<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SuratExport;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    private array $namaBulanIndo = [
        '', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des',
    ];

    public function index(Request $request)
    {
        // Sinkronkan dulu: surat yang tanggalnya sudah lewat otomatis "Selesai"
        Surat::sinkronStatusOtomatis();

        $query = Surat::query();

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [
                $request->tanggal_awal,
                $request->tanggal_akhir,
            ]);
        }

        $query
            ->when($request->filled('pengirim'), fn ($q) => $q->where('pengirim', 'like', '%' . $request->pengirim . '%'))
            ->when($request->filled('penerima'), fn ($q) => $q->where('penerima', 'like', '%' . $request->penerima . '%'))
            ->when($request->filled('status'),   fn ($q) => $q->where('status', $request->status));

        $surats = $query->latest()->get();

        // Daftar pengirim & penerima unik untuk autocomplete
        $daftarPengirim = Surat::distinct()->orderBy('pengirim')->pluck('pengirim');
        $daftarPenerima = Surat::distinct()->orderBy('penerima')->pluck('penerima');

        // ===== Tren bulanan (12 bulan terakhir) =====
        $suratPerBulan = Surat::all()->groupBy(function ($item) {
            return Carbon::parse($item->tanggal)->format('Y-m');
        })->map->count();

        $labelBulan = [];
        $dataBulan = [];

        $now = Carbon::now();

        for ($i = 11; $i >= 0; $i--) {
            $bulan = $now->copy()->subMonths($i);
            $key = $bulan->format('Y-m');

            $labelBulan[] = $this->namaBulanIndo[$bulan->month] . ' ' . $bulan->format('y');
            $dataBulan[] = $suratPerBulan[$key] ?? 0;
        }

        // ===== Statistik per instansi (pengirim terbanyak) =====
        $statistikInstansi = Surat::query()
            ->selectRaw('pengirim, COUNT(*) as jumlah')
            ->groupBy('pengirim')
            ->orderByDesc('jumlah')
            ->take(8)
            ->get();

        $maxInstansi = $statistikInstansi->max('jumlah') ?: 1;

        return view('laporan.index', compact(
            'surats',
            'labelBulan',
            'dataBulan',
            'statistikInstansi',
            'maxInstansi',
            'daftarPengirim',
            'daftarPenerima'
        ));
    }

    public function cetak(Request $request)
    {
        Surat::sinkronStatusOtomatis();

        $query = Surat::query();

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [
                $request->tanggal_awal,
                $request->tanggal_akhir,
            ]);
        }

        $query
            ->when($request->filled('pengirim'), fn ($q) => $q->where('pengirim', 'like', '%' . $request->pengirim . '%'))
            ->when($request->filled('penerima'), fn ($q) => $q->where('penerima', 'like', '%' . $request->penerima . '%'))
            ->when($request->filled('status'),   fn ($q) => $q->where('status', $request->status));

        $surats = $query->latest()->get();

        $pdf = Pdf::loadView('laporan.pdf', compact('surats'));

        return $pdf->download('laporan-serah-terima-surat.pdf');
    }

    public function excel()
{
   Surat::sinkronStatusOtomatis();

   return Excel::download(new SuratExport, 'laporan-serah-terima-surat.xlsx');
}
}
