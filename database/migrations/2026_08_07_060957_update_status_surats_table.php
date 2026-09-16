<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Normalisasi status lama ("Menunggu" & "Dikirim") menjadi "Diterima"
        //    dulu, supaya datanya tetap valid terhadap constraint enum lama
        //    sebelum kolomnya diubah.
        DB::table('surats')
            ->whereIn('status', ['Menunggu', 'Dikirim'])
            ->update(['status' => 'Diterima']);

        // 2. Ubah definisi kolom status: sekarang hanya ada "Diterima" & "Selesai".
        Schema::table('surats', function (Blueprint $table) {
            $table->enum('status', ['Diterima', 'Selesai'])
                ->default('Diterima')
                ->change();
        });

        // 3. Sinkronkan otomatis: surat yang tanggalnya sudah lewat dari hari ini
        //    langsung ditandai "Selesai".
        DB::table('surats')
            ->whereDate('tanggal', '<', now()->toDateString())
            ->update(['status' => 'Selesai']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->enum('status', ['Menunggu', 'Diterima', 'Dikirim'])
                ->default('Menunggu')
                ->change();
        });
    }
};
