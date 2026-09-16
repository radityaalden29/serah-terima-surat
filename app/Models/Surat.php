<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Surat extends Model
{
    protected $fillable = [

        'no_agenda',

        'jenis',

        'pengirim',

        'penerima',

        'perihal',

        'tanggal',

        'lampiran',

        'keterangan',

        'status'

    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Daftar status yang berlaku di sistem (hanya 2, dan berubah otomatis).
     */
    public const STATUS_DITERIMA = 'Diterima';
    public const STATUS_SELESAI  = 'Selesai';

    /**
     * Satu-satunya jenis surat yang berlaku di sistem sekarang.
     */
    public const JENIS_SURAT_MASUK = 'Surat Masuk';

    /*
    |--------------------------------------------------------------------------
    | Status & Jenis Otomatis
    |--------------------------------------------------------------------------
    | Status surat TIDAK lagi diisi/diedit manual lewat form/aksi.
    | Setiap kali surat disimpan (tambah/edit), status langsung dihitung
    | ulang oleh sistem berdasarkan tanggal perihal surat (kolom "tanggal"):
    |   - Jika tanggal surat sudah lewat dari hari ini -> "Selesai"
    |   - Jika belum lewat                              -> "Diterima"
    |
    | Jenis surat juga dikunci otomatis menjadi "Surat Masuk", karena sistem
    | ini sekarang hanya mencatat Surat Masuk (tidak ada Surat Keluar lagi).
    */

    protected static function booted(): void
    {
        static::saving(function (Surat $surat) {
            $surat->status = $surat->hitungStatusOtomatis();
            $surat->jenis  = self::JENIS_SURAT_MASUK;
        });
    }

    /**
     * Hitung status surat berdasarkan tanggal perihal surat.
     */
    public function hitungStatusOtomatis(): string
    {
        if ($this->tanggal) {
            $tanggalSurat = $this->tanggal instanceof Carbon
                ? $this->tanggal
                : Carbon::parse($this->tanggal);

            if ($tanggalSurat->startOfDay()->lt(now()->startOfDay())) {
                return self::STATUS_SELESAI;
            }
        }

        return self::STATUS_DITERIMA;
    }

    /**
     * Sinkronkan status semua surat yang tanggalnya sudah lewat menjadi
     * "Selesai" secara massal (1 query update). Dipanggil di controller
     * setiap halaman surat dibuka, sehingga status selalu up-to-date
     * secara otomatis tanpa perlu diedit manual lewat menu Aksi.
     */
    public static function sinkronStatusOtomatis(): void
    {
        static::query()
            ->where('status', '!=', self::STATUS_SELESAI)
            ->whereDate('tanggal', '<', now()->toDateString())
            ->update(['status' => self::STATUS_SELESAI]);
    }
}
