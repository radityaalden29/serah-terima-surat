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
        // 1. Ubah semua data lama yang jenisnya "Surat Keluar" menjadi "Surat Masuk",
        //    supaya datanya tetap valid terhadap constraint enum lama sebelum
        //    kolomnya diubah.
        DB::table('surats')
            ->where('jenis', 'Surat Keluar')
            ->update(['jenis' => 'Surat Masuk']);

        // 2. Ubah definisi kolom jenis: sekarang hanya ada "Surat Masuk".
        Schema::table('surats', function (Blueprint $table) {
            $table->enum('jenis', ['Surat Masuk'])
                ->default('Surat Masuk')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->enum('jenis', ['Surat Masuk', 'Surat Keluar'])
                ->change();
        });
    }
};
