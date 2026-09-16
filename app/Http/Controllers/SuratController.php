<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        return view('dashboard.index', compact(
            'totalSurat',
            'diterima',
            'selesai',
            'surats'
        ));
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
    | Form Tambah
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('surat.create');
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
        return view('surat.edit', compact('surat'));
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
        if ($surat->lampiran) {
            Storage::disk('public')->delete($surat->lampiran);
        }

        $surat->delete();

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
}
