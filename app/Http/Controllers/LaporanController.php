<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SuratExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Sinkronkan dulu: surat yang tanggalnya sudah lewat otomatis "Selesai"
        Surat::sinkronStatusOtomatis();

        $query = Surat::query();

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [
                $request->tanggal_awal,
                $request->tanggal_akhir
            ]);
        }

        $surats = $query->latest()->get();

        return view('laporan.index', compact('surats'));
    }

    public function cetak(Request $request)
    {
        Surat::sinkronStatusOtomatis();

        $query = Surat::query();

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [
                $request->tanggal_awal,
                $request->tanggal_akhir
            ]);
        }

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
