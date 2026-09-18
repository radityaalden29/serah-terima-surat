<?php

namespace App\Http\Controllers;

use App\Exports\SuratTemplateExport;
use App\Imports\SuratImport;
use App\Models\ActivityLog;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SuratController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        // Sinkronkan dulu: surat yang tanggalnya sudah lewat otomatis "Selesai"
        Surat::sinkronStatusOtomatis();

        $totalSurat = Surat::count();

        $diterima = Surat::where('status', 'Diterima')->count();

        $selesai = Surat::where('status', 'Selesai')->count();

        $surats = Surat::latest()->take(5)->get();

        // ===== Tren dibanding bulan lalu =====
        $semuaSurat = Surat::all(['tanggal', 'status']);

        $bulanIni  = now()->format('Y-m');
        $bulanLalu = now()->copy()->subMonth()->format('Y-m');

        $hitungBulan = function ($statusFilter, $bulanKey) use ($semuaSurat) {
            return $semuaSurat->filter(function ($s) use ($statusFilter, $bulanKey) {
                $cocokBulan = \Carbon\Carbon::parse($s->tanggal)->format('Y-m') === $bulanKey;
                $cocokStatus = $statusFilter ? $s->status === $statusFilter : true;
                return $cocokBulan && $cocokStatus;
            })->count();
        };

        $trenTotal    = $this->hitungPersenTren($hitungBulan(null, $bulanIni), $hitungBulan(null, $bulanLalu));
        $trenDiterima = $this->hitungPersenTren($hitungBulan('Diterima', $bulanIni), $hitungBulan('Diterima', $bulanLalu));
        $trenSelesai  = $this->hitungPersenTren($hitungBulan('Selesai', $bulanIni), $hitungBulan('Selesai', $bulanLalu));

        return view('dashboard.index', compact(
            'totalSurat',
            'diterima',
            'selesai',
            'surats',
            'trenTotal',
            'trenDiterima',
            'trenSelesai'
        ));
    }

    /**
     * Hitung persentase perubahan antara angka bulan ini vs bulan lalu.
     */
    private function hitungPersenTren(int $sekarang, int $lalu): int
    {
        if ($lalu === 0) {
            return $sekarang > 0 ? 100 : 0;
        }

        return (int) round((($sekarang - $lalu) / $lalu) * 100);
    }

    /*
    |--------------------------------------------------------------------------
    | Data Surat
    |--------------------------------------------------------------------------
    */

  public function index(Request $request)
{
    // Sinkronkan dulu: surat yang tanggalnya sudah lewat otomatis "Selesai"
    Surat::sinkronStatusOtomatis();

    $search = $request->search;
    $status = $request->status;
    $tanggalAwal = $request->tanggal_awal;
    $tanggalAkhir = $request->tanggal_akhir;

    $surats = Surat::query()

        // Pencarian
        ->when($search, function ($query) use ($search) {

            $query->where(function ($q) use ($search) {

                $q->where('no_agenda', 'like', "%{$search}%")
                  ->orWhere('pengirim', 'like', "%{$search}%")
                  ->orWhere('penerima', 'like', "%{$search}%");

            });

        })

        // Filter Status
        ->when($status, function ($query) use ($status) {

            $query->where('status', $status);

        })

        // Filter Tanggal Awal
        ->when($tanggalAwal, function ($query) use ($tanggalAwal) {

            $query->whereDate('tanggal', '>=', $tanggalAwal);

        })

        // Filter Tanggal Akhir
        ->when($tanggalAkhir, function ($query) use ($tanggalAkhir) {

            $query->whereDate('tanggal', '<=', $tanggalAkhir);

        })

        ->latest()
        ->paginate(10)
        ->withQueryString();

    // Kalau request dari live search (AJAX), balikin tabelnya aja
    if ($request->ajax()) {
        return view('surat._table', compact('surats'))->render();
    }

    return view('surat.index', compact('surats'));
}

    /*
    |--------------------------------------------------------------------------
    | Helper: Daftar Instansi (untuk autocomplete Pengirim/Penerima)
    |--------------------------------------------------------------------------
    */

    private function daftarInstansi()
    {
        $pengirim = Surat::query()->distinct()->pluck('pengirim');
        $penerima = Surat::query()->distinct()->pluck('penerima');

        return $pengirim->merge($penerima)
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Form Tambah
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $daftarInstansi = $this->daftarInstansi();

        return view('surat.create', compact('daftarInstansi'));
    }

    /*
    |--------------------------------------------------------------------------
    | Simpan Data
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
{
    $request->validate([
        'no_agenda' => 'required|unique:surats',
        'pengirim' => 'required',
        'penerima' => 'required',
        'perihal' => 'required',
        'tanggal' => 'required|date',
        'lampiran' => 'nullable|mimes:pdf|max:2048',
    ]);

    // Status & jenis tidak diinput manual. Nilainya diisi otomatis oleh
    // model (Surat::booted -> saving): status dari tanggal surat,
    // jenis selalu "Surat Masuk".
    $data = $request->except(['status', 'jenis']);

    if ($request->hasFile('lampiran')) {
        $data['lampiran'] = $request->file('lampiran')
            ->store('lampiran', 'public');
    }

    Surat::create($data);

    ActivityLog::create([
        'aksi' => ActivityLog::AKSI_TAMBAH,
        'no_agenda' => $data['no_agenda'],
        'keterangan' => "Surat dari \"{$data['pengirim']}\" untuk \"{$data['penerima']}\" ditambahkan.",
    ]);

    return redirect()
        ->route('surat.index')
        ->with('success', 'Surat berhasil ditambahkan.');
}

    /*
    |--------------------------------------------------------------------------
    | Detail
    |--------------------------------------------------------------------------
    */

    public function show(Surat $surat)
    {
        Surat::sinkronStatusOtomatis();

        $surat->refresh();

        return view('surat.show', compact('surat'));
    }

    /*
    |--------------------------------------------------------------------------
    | Form Edit
    |--------------------------------------------------------------------------
    */

    public function edit(Surat $surat)
    {
        $daftarInstansi = $this->daftarInstansi();

        return view('surat.edit', compact('surat', 'daftarInstansi'));
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

  public function update(Request $request, Surat $surat)
{
    $request->validate([
        'no_agenda' => 'required|unique:surats,no_agenda,' . $surat->id,
        'pengirim' => 'required',
        'penerima' => 'required',
        'perihal' => 'required',
        'tanggal' => 'required|date',
        'lampiran' => 'nullable|mimes:pdf|max:2048',
    ]);

    // Status & jenis tidak diinput manual. Nilainya diisi ulang otomatis
    // oleh model (Surat::booted -> saving): status dari tanggal surat,
    // jenis selalu "Surat Masuk".
    $data = $request->except(['status', 'jenis']);

    if ($request->hasFile('lampiran')) {

        if ($surat->lampiran) {
            Storage::disk('public')->delete($surat->lampiran);
        }

        $data['lampiran'] = $request->file('lampiran')
            ->store('lampiran', 'public');
    }

    $surat->update($data);

    ActivityLog::create([
        'aksi' => ActivityLog::AKSI_UBAH,
        'no_agenda' => $surat->no_agenda,
        'keterangan' => "Data surat \"{$surat->perihal}\" diperbarui.",
    ]);

    return redirect()
        ->route('surat.index')
        ->with('success', 'Surat berhasil diperbarui.');
}

    /*
    |--------------------------------------------------------------------------
    | Hapus
    |--------------------------------------------------------------------------
    */

    public function destroy(Surat $surat)
{
    try {
        $noAgenda = $surat->no_agenda;
        $perihal = $surat->perihal;

        if ($surat->lampiran) {
            Storage::disk('public')->delete($surat->lampiran);
        }

        $surat->delete();

        ActivityLog::create([
            'aksi' => ActivityLog::AKSI_HAPUS,
            'no_agenda' => $noAgenda,
            'keterangan' => "Surat \"{$perihal}\" dihapus dari sistem.",
        ]);

        return redirect()
            ->route('surat.index')
            ->with('success', 'Surat berhasil dihapus.');

    } catch (\Exception $e) {

        return redirect()
            ->route('surat.index')
            ->with('error', 'Surat gagal dihapus. Silakan coba lagi.');
    }
}
    /*
    |--------------------------------------------------------------------------
    | Laporan
    |--------------------------------------------------------------------------
    */

    public function laporan()
    {
        Surat::sinkronStatusOtomatis();

        $surats = Surat::latest()->get();

        return view('laporan.index', compact('surats'));
    }

    /*
    |--------------------------------------------------------------------------
    | Import Excel
    |--------------------------------------------------------------------------
    */

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        $import = new SuratImport();

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Exception $e) {
            return redirect()
                ->route('surat.index')
                ->with('error', 'Gagal membaca file. Pastikan format file sesuai template.');
        }

        $jumlahBerhasil = $import->imported;
        $jumlahDuplikat = $import->duplikat;
        $jumlahGagal = $import->failures()->count();

        if ($jumlahBerhasil > 0) {
            ActivityLog::create([
                'aksi' => ActivityLog::AKSI_IMPOR,
                'no_agenda' => '-',
                'keterangan' => "{$jumlahBerhasil} surat berhasil diimpor dari file Excel/CSV.",
            ]);
        }

        $pesan = "{$jumlahBerhasil} surat berhasil diimpor.";

        if ($jumlahDuplikat > 0) {
            $pesan .= " {$jumlahDuplikat} baris dilewati karena No Agenda sudah ada.";
        }

        if ($jumlahGagal > 0) {
            $pesan .= " {$jumlahGagal} baris dilewati karena data tidak lengkap.";
        }

        if ($jumlahBerhasil === 0) {
            return redirect()->route('surat.index')->with('error', $pesan);
        }

        return redirect()->route('surat.index')->with('success', $pesan);
    }

    public function downloadTemplate()
    {
        return Excel::download(new SuratTemplateExport, 'template-import-surat.xlsx');
    }

    /*
    |--------------------------------------------------------------------------
    | Log Aktivitas
    |--------------------------------------------------------------------------
    */

    public function activityLog()
    {
        $logs = ActivityLog::latest()->paginate(15);

        return view('log-aktivitas.index', compact('logs'));
    }
}
